<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
use App\Models\Po;
use App\Models\PoDetail;
use App\Models\Mitra;
use App\Models\PeriodeMasak;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;

Carbon::setLocale('id');
class RabController extends Controller
{
    public function index()
    {
        $periode = PeriodeMasak::orderBy('tanggal_awal', 'desc')->get();
        $title = 'Rencana Anggaran Biaya (RAB)';
        return view('app.rab.index', compact('title', 'periode'));
    }

    public function generate(Request $request)
    {
        $tanggalParam = explode(',', $request->get('tanggal'));

        if (count($tanggalParam) === 0) {
            return redirect()->back()->with('error', 'Tanggal tidak valid.');
        }

        if (count($tanggalParam) < 2) {
            $tanggalParam[1] = $tanggalParam[0];
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

        $view = view('app.rab.pdf', compact('dataBahanPangan', 'tanggalParam', 'jenis'))->render();

        return PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 20,
                'margin-bottom' => 20,
                'margin-left'   => 25,
                'margin-right'  => 20,
            ])
            ->setPaper('A4', 'portrait')
            ->setOption('title', $title)
            ->inline('RAB.pdf');
    }


    public function approve()
    {
        $periode = PeriodeMasak::orderBy('tanggal_awal', 'desc')->get();

        $title = 'Approve RAB';
        return view('app.rab.approve', compact('title', 'periode'));
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

        $periode = PeriodeMasak::where([
            ['tanggal_awal', '=', $tanggal[0]],
            ['tanggal_akhir', '=', $tanggal[1]]
        ])->first();

        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->whereBetween('tanggal', $tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();

        $dataBahanPangan = [];
        foreach ($rancangan as $r) {
            $jumlah = $r->jumlah;

            foreach ($r->rancanganMenu as $rm) {
                $menu = $rm->menu;
                if ($menu) {
                    foreach ($menu->resep as $resep) {
                        $bahanPangan = $resep->bahanPangan;
                        if ($bahanPangan) {
                            if (in_array($bahanPangan->id, array_keys($dataBahanPangan))) {
                                $gramasi = ($resep->gramasi * $jumlah);
                                $dataBahanPangan[$bahanPangan->id]['jumlah'] += $gramasi;
                            } else {
                                $dataBahanPangan[$bahanPangan->id] = [
                                    'nama' => $bahanPangan->nama,
                                    'satuan' => $bahanPangan->satuan,
                                    'harga' => $bahanPangan->harga_jual,
                                    'jumlah' => ($resep->gramasi * $jumlah),
                                ];
                            }
                        }
                    }
                }
            }
        }

        uasort($dataBahanPangan, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        $view = view('app.rab.approve-list', compact('dataBahanPangan', 'periode'))->render();
        return response()->json(['view' => $view]);
    }

    public function approved(Request $request)
    {
        $tanggal = explode('_', $request->periode);

        $tanggalAwal = $tanggal[0];
        $tanggalAkhir = $tanggal[1];

        $periode = PeriodeMasak::where([
            ['tanggal_awal', '=', $tanggalAwal],
            ['tanggal_akhir', '=', $tanggalAkhir]
        ])->first();

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        $periode->approved = $request->approve;
        $periode->save();

        return response()->json([
            'success' => true,
            'message' => 'RAB periode ke ' . $periode->periode_ke . ' berhasil ' . ($request->approve ? 'disetujui' : 'ditolak')
        ]);
    }
    public function PO(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $periode = PeriodeMasak::where('approved', 1)
            ->where('tanggal_awal', '<=', $tanggalAkhir)
            ->where('tanggal_akhir', '>=', $tanggalAwal)
            ->get();

        if (!$periode) {
            return response('<div class="alert alert-warning">Belum ada periode yang disetujui.</div>');
        }

        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan.mitra'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'ASC')
            ->get();

        if ($rancangan->isEmpty()) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Belum ada rancangan pada periode ini.'
            ]);
        }   
        $dataBahanPangan = [];

        foreach ($rancangan as $r) {
            $jumlahRancangan = $r->jumlah ?? 1;

            foreach ($r->rancanganMenu as $rm) {
                $menu = $rm->menu;
                if (!$menu) continue;

                foreach ($menu->resep as $resep) {
                    $bp = $resep->bahanPangan;
                    if (!$bp) continue;

                    $bpId = $bp->id;

                    if (!isset($dataBahanPangan[$bpId])) {
                        $mitraList = [];
                        foreach ($bp->mitra as $mitra) {
                            $mitraList[] = $mitra; 
                        }

                        $dataBahanPangan[$bpId] = [
                            'bahan_pangan_id' => $bpId,
                            'nama' => $bp->nama,
                            'satuan' => $bp->satuan,
                            'harga' => $bp->harga_jual ?? 0,
                            'jumlah' => 0,
                            'mitra' => $mitraList,
                        ];
                    }

                    $dataBahanPangan[$bpId]['jumlah'] += ($resep->gramasi ?? 0) * $jumlahRancangan;
                }
            }
        }

        $title = 'PO';
        return view('app.rab.po_tabel', compact('dataBahanPangan', 'title'));
    }
    public function simpanPO(Request $request)
    {
        $po = \App\Models\Po::firstOrCreate(
            ['tanggal' => now()->toDateString()],
            ['total_harga' => 0, 'status_bayar' => 'unpaid']
        );

        $jumlahs     = $request->input('jumlah_input');
        $hargaSatuan = $request->input('harga_satuan');
        $kebutuhans  = $request->input('jumlah_kebutuhan');
        $mitraIds    = $request->input('mitra_id');

        $totalKeseluruhan = 0;

        foreach ($jumlahs as $bpId => $jmlInput) {
        $mitraId = $mitraIds[$bpId] ?? null;

        // Abaikan jika input 0 atau mitra_id null
        if ($jmlInput <= 0 || !$mitraId) continue;

        $harga     = $hargaSatuan[$bpId] ?? 0;
        $kebutuhan = $kebutuhans[$bpId] ?? 0;
        $total     = $harga * $jmlInput;

        $totalKeseluruhan += $total;

        \App\Models\PoDetail::updateOrCreate(
            [
                'po_id'           => $po->id,
                'bahan_pangan_id' => $bpId,
            ],
            [
                'harga_satuan' => $harga,
                'jumlah'       => $kebutuhan,
                'jumlah_input' => $jmlInput,
                'total_harga'  => $total,
                'status_bayar' => 'unpaid',
                'mitra_id'     => $mitraId,
            ]
        );
        }


        $po->update(['total_harga' => $totalKeseluruhan]);

        return response()->json([
            'message' => 'PO berhasil disimpan.',
            'po_id'   => $po->id,
            'total'   => $totalKeseluruhan
        ]);
    }

    public function detailPO($id)
    {
        $title = 'Detail PO';

        // Ambil PO utama beserta detail yang jumlah_input > 0
        $po = Po::with(['poDetail' => function($q) {
            $q->where('jumlah_input', '>', 0)
            ->with(['bahanPangan', 'mitra']);
        }])->findOrFail($id);

        // Gunakan PO yang sama sebagai referensi untuk Blade
        $referensiPOs = collect([$po]);
        $bahanPangan = \App\Models\BahanPangan::orderBy('nama')->get();

        return view('app.rab.po_detail', compact('po', 'referensiPOs', 'title', 'bahanPangan'));
    }
    public function updatePO(Request $request)
    {
            $request->validate([
            'id' => 'required|exists:po_details,id',
            'bahan_pangan_id' => 'required|exists:bahan_pangans,id',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah_input' => 'required|numeric|min:0',
        ]);


        $detail = PoDetail::findOrFail($request->id);
        $detail->bahan_pangan_id = $request->bahan_pangan_id;
        $detail->harga_satuan    = $request->harga_satuan;
        $detail->jumlah_input    = $request->jumlah_input;
        $detail->total_harga     = $request->harga_satuan * $request->jumlah_input;
        $detail->save();

        // Update total PO
        $total = PoDetail::where('po_id', $detail->po_id)->sum('total_harga');
        $detail->po->update(['total_harga' => $total]);

        return redirect()->back()->with('success', 'Detail PO berhasil diperbarui.');
    }
    public function cetak_detail($id)
    {
        // Ambil detail PO
        $detail = PoDetail::with('po')->findOrFail($id);

        // Ambil PO master dari detail
        $po = Po::with('poDetail.bahanPangan')->findOrFail($detail->po->id);
        $title = 'Cetak Po';
        return view('app.rab.po_cetak_detail', compact('po','title'));
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'po_detail_id' => 'required|exists:po_detail,id',
            'jumlah_bayar' => 'required|numeric|min:1'
        ]);

        $detail = PoDetail::findOrFail($request->po_detail_id);

        // Update jumlah_input (bayar sebagian)
        $detail->jumlah_input = ($detail->jumlah_input ?? 0) + $request->jumlah_bayar;

        // Batasi agar tidak lebih dari total_harga
        if ($detail->jumlah_input >= $detail->total_harga) {
            $detail->jumlah_input = $detail->total_harga;
            $detail->status_bayar = 'PAID';
        } elseif ($detail->jumlah_input > 0) {
            $detail->status_bayar = 'PARTIAL';
        } else {
            $detail->status_bayar = 'UNPAID';
        }

        $detail->save();

        return back()->with('success', 'Pembayaran berhasil disimpan');
    }

    public function cetakPO()
    {
        $title = 'Cetak PO';

        $pos = Po::with(['poDetail' => function($q) {
            $q->where('jumlah_input', '>', 0)->with('bahanPangan');
        }])
        ->whereHas('poDetail', function($q) {
            $q->where('jumlah_input', '>', 0);
        })
        ->orderBy('tanggal', 'asc')
        ->get();

        $view = view('app.rab.po_cetak', compact('pos', 'title'))->render();

        return PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 20,
                'margin-bottom' => 20,
                'margin-left'   => 25,
                'margin-right'  => 20,
            ])
            ->setPaper('A4', 'portrait')
            ->setOption('title', $title)
            ->inline('RAB.pdf');
    }




}
