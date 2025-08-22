<?php

namespace App\Http\Controllers;

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
            $periode = PeriodeMasak::with('')->orderBy('tanggal_awal', 'desc')->get();
            return datatables()->of($periode)->get();
        }

        $title = 'Rancangan Menu';
        return view('app.rancang-menu.index', compact('title'));
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

        $kelompokPemanfaat = KelompokPemanfaat::with('pemanfaat')->get();
        $menu = Menu::orderBy('nama', 'asc')->get();

        $title = 'Tambah Rancangan Menu';
        return view('app.rancang-menu.create', compact('title', 'periode', 'kelompokPemanfaat', 'menu', 'today'));
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

            foreach ($rancanganMenu as $rm) {
                $kelompokPemanfaat = explode('|', $rm['kelompok_pemanfaat']);
                $kelompokPemanfaatId = $kelompokPemanfaat[0];
                $jumlahPemanfaat = $kelompokPemanfaat[1];

                $rancangan = Rancangan::create([
                    'periode_masak_id' => $periode_id,
                    'kelompok_pemanfaat_id' => $kelompokPemanfaatId,
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
    public function show(Rancangan $rancangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rancangan $rancangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rancangan $rancangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rancangan $rancangan)
    {
        //
    }
}
