<?php

namespace App\Http\Controllers;

use App\Models\DataPemanfaat;
use App\Models\KelompokPemanfaat;
use App\Models\Menu;
use App\Models\PeriodeMasak;
use App\Models\Rancangan;
use App\Models\RancanganMenu;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RancanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $tanggal_awal = request()->get('tanggal_awal', date('Y-m-d'));
            $tanggal_akhir = request()->get('tanggal_akhir', date('Y-m-d', strtotime('+14 days')));

            $rancanganMenu = Rancangan::select('rancangans.*', 'periode_masaks.periode_ke', 'periode_masaks.tanggal_awal', 'periode_masaks.tanggal_akhir')
                ->join('periode_masaks', 'rancangans.periode_masak_id', '=', 'periode_masaks.id')
                ->with(['dataPemanfaat', 'rancanganMenu.menu.resep.bahanPangan'])
                ->orderBy('tanggal', 'desc')
                ->get();

            return datatables()->of($rancanganMenu)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/app/rancang-menu/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus ms-2" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
                })
                ->editColumn('periode_ke', function ($row) {
                    return 'Periode ke ' . str_pad($row->periode_ke, 2, '0', STR_PAD_LEFT);
                })
                ->addColumn('menu', function ($row) {
                    $menu = '<div class="demo-inline-spacing">';
                    foreach ($row->rancanganMenu as $rm) {
                        $_menu = '<span class="badge text-bg-primary">' . $rm->menu->nama . '</span>';
                        $menu .= $_menu;
                    }
                    $menu .= '</div>';

                    return $menu;
                })
                ->rawColumns(['action', 'menu'])
                ->make(true);
        }

        $periode = PeriodeMasak::where([
            ['tanggal_awal', '<=', date('Y-m-d')],
            ['tanggal_akhir', '>=', date('Y-m-d')]
        ])->first();

        $title = 'Rancangan Menu';
        return view('app.rancang-menu.index', compact('title', 'periode'));
    }

    public function approve()
    {
        $periode = PeriodeMasak::orderBy('tanggal_awal', 'desc')->get();

        $title = 'Approve Rancangan Menu';
        return view('app.rancang-menu.approve', compact('title', 'periode'));
    }

    public function approveList()
    {
        $tanggal = explode(',', request()->get('tanggal'));
        if (count($tanggal) === 0) {
            return response()->json(['error' => 'Tanggal tidak boleh kosong.'], 422);
        }

        if (count($tanggal) < 2) {
            $tanggal[1] = $tanggal[0];
        }

        $rancangan = Rancangan::with(['dataPemanfaat', 'rancanganMenu.menu'])
            ->whereBetween('tanggal', [$tanggal[0], $tanggal[1]])
            ->where('approved', 0)
            ->orderBy('tanggal', 'ASC')
            ->get();

        $view = view('app.rancang-menu.approve-list', compact('rancangan'))->render();
        return response()->json(['view' => $view]);
    }

    public function approved(Request $request)
    {
        $id = $request->id;
        if (count($id) === 0) {
            return response()->json(['error' => 'Data tidak boleh kosong.'], 422);
        }

        $rancangan = Rancangan::whereIn('id', $id)->update(['approved' => 1]);
        return response()->json(['success' => true, 'message' => 'Rancangan menu berhasil disetujui.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $today = (request()->get('tanggal')) ? request()->get('tanggal') : date('Y-m-d');
        $periode = PeriodeMasak::where([
            ['tanggal_awal', '<=', $today],
            ['tanggal_akhir', '>=', $today]
        ])->first();

        $dataPemanfaat = DataPemanfaat::orderBy('nama_lembaga', 'asc')->get();
        $menu = Menu::orderBy('nama', 'asc')->get();

        $title = 'Tambah Rancangan Menu';
        return view('app.rancang-menu.create', compact('title', 'periode', 'dataPemanfaat', 'menu', 'today'));
    }

    public function getPeriode($tanggal_periode)
    {
        $tanggal = new Tanggal();
        $periode = PeriodeMasak::where([
            ['tanggal_awal', '<=', $tanggal_periode],
            ['tanggal_akhir', '>=', $tanggal_periode]
        ])->first();

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        $title = $tanggal->tglLatin($periode->tanggal_awal) . ' - ' . $tanggal->tglLatin($periode->tanggal_akhir);
        $subtitle = 'Tambah Rancangan Menu Periode ke ' . str_pad($periode->periode_ke, 2, '0', STR_PAD_LEFT);
        $waktu_awal = strtotime($periode->tanggal_awal);
        $waktu_akhir = strtotime($periode->tanggal_akhir);

        return response()->json([
            'success' => true,
            'title' => $title,
            'subtitle' => $subtitle,
            'waktu_awal' => $waktu_awal,
            'waktu_akhir' => $waktu_akhir,
            'tanggal_awal' => $periode->tanggal_awal,
            'tanggal_akhir' => $periode->tanggal_akhir,
            'periode' => $periode,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'tanggal',
            'periode_id',
            'rancangan',
        ]);

        $validate = Validator::make($data, [
            'tanggal' => 'required|date',
            'periode_id' => 'required|exists:periode_masaks,id',
            'rancangan' => 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $tanggal = $request->tanggal;
            $periode_id = $request->periode_id;
            $rancanganMenu = $request->rancangan;

            $cekRancangan = Rancangan::where('periode_masak_id', $periode_id)
                ->where('tanggal', $tanggal)
                ->exists();

            if ($cekRancangan) {
                return response()->json(['error' => 'Rancangan menu untuk tanggal ini sudah ada'], 422);
            }

            foreach ($rancanganMenu as $rm) {
                $dataPemanfaat = explode('|', $rm['data_pemanfaat']);
                $dataPemanfaatId = $dataPemanfaat[0];
                $jumlahPemanfaat = $dataPemanfaat[1];

                $rancangan = Rancangan::create([
                    'periode_masak_id' => $periode_id,
                    'data_pemanfaat_id' => $dataPemanfaatId,
                    'tanggal' => $tanggal,
                    'jumlah' => $jumlahPemanfaat,
                ]);

                $rancangan_menu = [];
                $menu = json_decode($rm['menu'], true);
                foreach ($menu as $m) {
                    $rancangan_menu[] = [
                        'rancangan_id' => $rancangan->id,
                        'menu_id' => $m['id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                RancanganMenu::insert($rancangan_menu);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Rancangan menu berhasil disimpan.',
                'tanggal' => date('Y-m-d', strtotime('+1 day', strtotime($tanggal))),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rancangan $rancang_menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rancangan $rancang_menu)
    {
        $rancang_menu = $rancang_menu->load(['periode', 'dataPemanfaat', 'rancanganMenu.menu']);
        $menu = Menu::orderBy('nama', 'asc')->get();

        $title = 'Edit Rancangan Menu';
        return view('app.rancang-menu.edit', compact('title', 'rancang_menu', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rancangan $rancang_menu)
    {
        $data = $request->only([
            'tanggal',
            'menu',
        ]);

        $validate = Validator::make($data, [
            'tanggal' => 'required|date',
            'menu' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        $rancangan_menu = [];
        $menu = json_decode($data['menu'], true);
        foreach ($menu as $m) {
            $rancangan_menu[] = [
                'rancangan_id' => $rancang_menu->id,
                'menu_id' => $m['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (count($rancangan_menu) > 0) {
            RancanganMenu::where('rancangan_id', $rancang_menu->id)->delete();
            RancanganMenu::insert($rancangan_menu);
        }

        $rancang_menu->update([
            'tanggal' => $data['tanggal'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rancangan menu berhasil disimpan.',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rancangan $rancang_menu)
    {
        $rancangan = Rancangan::where('tanggal', $rancang_menu->tanggal)
            ->where('periode_masak_id', $rancang_menu->periode_masak_id)
            ->get();

        if (count($rancangan) > 0) {
            RancanganMenu::whereIn('rancangan_id', $rancangan->pluck('id')->toArray())->delete();
            Rancangan::whereIn('id', $rancangan->pluck('id')->toArray())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rancangan menu berhasil dihapus.',
            ], 200);
        }

        return response()->json([
            'error' => 'Rancangan menu tidak ditemukan.',
        ], 404);
    }
}
