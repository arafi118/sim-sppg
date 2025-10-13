<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use App\Models\KelompokPangan;
use App\Models\Mitra;
use App\Models\PeriodeMasak;
use App\Models\Rancangan;
use App\Utils\Tanggal;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $mitra = Mitra::with('bahanPangan')->get();

            return datatables()->of($mitra)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/app/mitra/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus ms-2" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Daftar Mitra';
        return view('app.mitra.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompokPangan = KelompokPangan::with([
            'bahanPangan' => function ($query) {
                $query->orderBy('nama', 'asc');
            }
        ])->orderBy('nama', 'asc')->get();

        $title = 'Tambah Mitra';
        return view('app.mitra.create', compact('title', 'kelompokPangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'bahan_pangan_id',
            "nama_bahan",
            "harga_beli",
            "nama_mitra",
            "telpon",
            "alamat",
        ]);

        $validate = Validator::make($data, [
            'nama_bahan' => 'required',
            'harga_beli' => 'required',
            'nama_mitra' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $mitra = Mitra::create([
            'bahan_pangan_id' => $data['bahan_pangan_id'],
            'nama' => $data['nama_mitra'],
            'alamat' => $data['alamat'],
            'telpon' => $data['telpon'],
            'harga_beli' => str_replace(',', '', $data['harga_beli']),
        ]);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $data['bahan_pangan_id'])->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $data['bahan_pangan_id'])->update([
            'harga_jual' => $hargaBahanTertinggi->harga_beli,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil disimpan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        $kelompokPangan = KelompokPangan::with([
            'bahanPangan' => function ($query) {
                $query->orderBy('nama', 'asc');
            }
        ])->orderBy('nama', 'asc')->get();

        $title = 'Edit Mitra';
        return view('app.mitra.edit', compact('title', 'mitra', 'kelompokPangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $mitra)
    {
        $data = $request->only([
            "bahan_pangan_id",
            "nama_bahan",
            "harga_beli",
            "nama_mitra",
            "telpon",
            "alamat",
        ]);

        $validate = Validator::make($data, [
            'nama_bahan' => 'required',
            'harga_beli' => 'required',
            'nama_mitra' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        Mitra::where('id', $mitra->id)->update([
            'bahan_pangan_id' => $data['bahan_pangan_id'],
            'nama' => $data['nama_mitra'],
            'alamat' => $data['alamat'],
            'telpon' => $data['telpon'],
            'harga_beli' => str_replace(',', '', $data['harga_beli']),
        ]);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $data['bahan_pangan_id'])->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $data['bahan_pangan_id'])->update([
            'harga_jual' => $hargaBahanTertinggi->harga_beli,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra)
    {
        Mitra::destroy($mitra->id);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $mitra->bahan_pangan_id)->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $mitra->bahan_pangan_id)->update([
            'harga_jual' => $hargaBahanTertinggi ? $hargaBahanTertinggi->harga_beli : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => $mitra->nama . ' berhasil dihapus!',
        ]);
    }

    public function daftarMenu()
    {
        $periode = PeriodeMasak::where('tanggal_awal', '<=', date('Y-m-d'))->where('tanggal_akhir', '>=', date('Y-m-d'))->first();
        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->where('approved', 1)
            ->orderBy('tanggal', 'ASC')
            ->get();

        $title = 'Daftar Menu';
        return view('app.mitra.daftar-menu')->with(compact('title', 'rancangan'));
    }

    public function rab()
    {
        $periode = PeriodeMasak::where('tanggal_awal', '<=', date('Y-m-d'))->where('tanggal_akhir', '>=', date('Y-m-d'))->first();

        $title = 'Rencana Anggaran Biaya (RAB)';
        return view('app.mitra.rab')->with(compact('title', 'periode'));
    }

    public function generate()
    {
        $tanggalParam = explode(',', request()->get('tanggal'));

        if (count($tanggalParam) === 0) {
            return redirect()->back()->with('error', 'Tanggal tidak valid.');
        }

        $orientasi = 'landscape';
        if (count($tanggalParam) == '2') {
            $tanggal_awal = new DateTime($tanggalParam[0]);
            $tanggal_akhir = new DateTime($tanggalParam[1]);
            $selisih = $tanggal_awal->diff($tanggal_akhir);

            $daftarTanggal = [];
            for ($i = 0; $i <= $selisih->days; $i++) {
                $TanggalPeriode = date('Y-m-d', strtotime('+ ' . $i . ' days', strtotime($tanggal_awal->format('Y-m-d'))));

                $daftarTanggal[] = [
                    'hari' => date('d', strtotime($TanggalPeriode)),
                    'nama_hari' => Tanggal::namaHari($TanggalPeriode),
                    'tanggal' => $TanggalPeriode
                ];
            }

            // Hanya ambil rancangan yang sudah approved
            $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
                ->whereBetween('tanggal', $tanggalParam)
                ->where('approved', 1)
                ->orderBy('tanggal', 'ASC')
                ->get();

            // Kalau tidak ada rancangan approved → balik ke index
            if ($rancangan->isEmpty()) {
                return redirect()->route('rab.index')->with('error', 'Belum ada rancangan yang disetujui.');
            }

            $jenis = ($tanggalParam[0] === $tanggalParam[1]) ? 'Harian' : 'Periode';

            $dataBahanPangan = [];
            foreach ($rancangan as $r) {
                $jumlah = $r->jumlah;

                foreach ($r->rancanganMenu as $rm) {
                    $menu = $rm->menu;
                    if ($menu) {
                        foreach ($menu->resep as $resep) {
                            $bahanPangan = $resep->bahanPangan;
                            if ($bahanPangan) {
                                if (isset($dataBahanPangan[$bahanPangan->id])) {
                                    if (isset($dataBahanPangan[$bahanPangan->id]['jumlah'][$r->tanggal])) {
                                        $dataBahanPangan[$bahanPangan->id]['jumlah'][$r->tanggal] += ($resep->gramasi * $jumlah);
                                    } else {
                                        $dataBahanPangan[$bahanPangan->id]['jumlah'][$r->tanggal] = ($resep->gramasi * $jumlah);
                                    }
                                } else {
                                    $dataBahanPangan[$bahanPangan->id] = [
                                        'nama'   => $bahanPangan->nama,
                                        'satuan' => $bahanPangan->satuan,
                                        'harga'  => $bahanPangan->harga_jual,
                                        'jumlah' => [
                                            $r->tanggal => ($resep->gramasi * $jumlah)
                                        ],
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            uasort($dataBahanPangan, fn($a, $b) => strcmp($a['nama'], $b['nama']));

            if ($jenis === 'Harian') {
                $title = "Rab Harian - " . Carbon::parse($tanggalParam[0])->translatedFormat('d F Y');
            } else {
                $title = "Rab Periode - " .
                    Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') .
                    " s.d " .
                    Carbon::parse($tanggalParam[1])->translatedFormat('d F Y');
            }

            $view = view('app.mitra.cetak-rab-full', compact('dataBahanPangan', 'tanggalParam', 'daftarTanggal', 'jenis'))->render();
        } else {
            $tanggalParam[1] = $tanggalParam[0];

            // Hanya ambil rancangan yang sudah approved
            $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
                ->whereBetween('tanggal', $tanggalParam)
                ->where('approved', 1)
                ->orderBy('tanggal', 'ASC')
                ->get();

            // Kalau tidak ada rancangan approved → balik ke index
            if ($rancangan->isEmpty()) {
                return redirect()->route('rab.index')->with('error', 'Belum ada rancangan yang disetujui.');
            }

            $jenis = ($tanggalParam[0] === $tanggalParam[1]) ? 'Harian' : 'Periode';

            $dataBahanPangan = [];
            foreach ($rancangan as $r) {
                $jumlah = $r->jumlah;

                foreach ($r->rancanganMenu as $rm) {
                    $menu = $rm->menu;
                    if ($menu) {
                        foreach ($menu->resep as $resep) {
                            $bahanPangan = $resep->bahanPangan;
                            if ($bahanPangan) {
                                if (isset($dataBahanPangan[$bahanPangan->id])) {
                                    $dataBahanPangan[$bahanPangan->id]['jumlah'] += ($resep->gramasi * $jumlah);
                                } else {
                                    $dataBahanPangan[$bahanPangan->id] = [
                                        'nama'   => $bahanPangan->nama,
                                        'satuan' => $bahanPangan->satuan,
                                        'harga'  => $bahanPangan->harga_jual,
                                        'jumlah' => ($resep->gramasi * $jumlah),
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            uasort($dataBahanPangan, fn($a, $b) => strcmp($a['nama'], $b['nama']));

            if ($jenis === 'Harian') {
                $title = "Rab Harian - " . Carbon::parse($tanggalParam[0])->translatedFormat('d F Y');
            } else {
                $title = "Rab Periode - " .
                    Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') .
                    " s.d " .
                    Carbon::parse($tanggalParam[1])->translatedFormat('d F Y');
            }

            $orientasi = 'portrait';
            $view = view('app.mitra.cetak-rab-per-tanggal', compact('dataBahanPangan', 'tanggalParam', 'jenis'))->render();
        }

        return PDF::loadHTML($view)
            ->setOptions([
                'header-line' => true,
                'margin-top'     => 20,
                'margin-bottom'  => 16,
                'margin-left'    => 12,
                'margin-right'   => 10,
                'enable-local-file-access' => true,
            ])
            ->setPaper('A4', $orientasi)
            ->setOption('title', $title)
            ->inline('RAB.pdf');
    }
}
