<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
use App\Models\Po;
use App\Models\PoDetail;
use App\Models\BahanPangan;
use App\Models\Mitra;
use App\Models\PeriodeMasak;
use App\Utils\Tanggal;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;

Carbon::setLocale('id');
class RabController extends Controller
{
    public function index()
    {
        $periode = PeriodeMasak::orderBy('periode_ke', 'ASC')->get();
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

        $view = view('app.rab.pdf', compact('dataBahanPangan', 'tanggalParam', 'daftarTanggal', 'jenis'))->render();

        return PDF::loadHTML($view)
            ->setOptions([
                'header-line' => true,
                'margin-top'     => 20,
                'margin-bottom'  => 16,
                'margin-left'    => 12,
                'margin-right'   => 10,
                'enable-local-file-access' => true,
            ])
            ->setPaper('A4', 'landscape')
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
        $po = Po::firstOrCreate(
            ['tanggal' => now()->toDateString()],
            ['total_harga' => 0, 'status_bayar' => 'unpaid']
        );

        $jumlahs     = $request->input('jumlah_input');
        $hargaSatuan = $request->input('harga_satuan');
        $kebutuhans  = $request->input('jumlah_kebutuhan');

        $totalKeseluruhan = 0;
        foreach ($jumlahs as $bpId => $jmlInput) {
            if ($jmlInput <= 0) continue;

            $harga     = $hargaSatuan[$bpId] ?? 0;
            $kebutuhan = $kebutuhans[$bpId] ?? 0;
            $total     = $harga * $jmlInput;
            $totalKeseluruhan += $total;

            PoDetail::updateOrCreate(
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

        $po = Po::with(['poDetail' => function ($q) {
            $q->where('jumlah_input', '>', 0)
                ->with(['bahanPangan', 'mitra']);
        }])->findOrFail($id);

        $referensiPOs = Po::with(['poDetail' => function ($q) {
            $q->where('jumlah_input', '>', 0)
                ->with(['bahanPangan', 'mitra']);
        }])
            ->whereHas('poDetail', function ($q) {
                $q->where('jumlah_input', '>', 0);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $bahanPangan = BahanPangan::orderBy('nama')->get();
        $mitras = Mitra::orderBy('nama')->get(); // semua mitra

        return view('app.rab.po_detail', compact('po', 'referensiPOs', 'title', 'bahanPangan', 'mitras'));
    }


    public function updatePO(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:po_details,id',
            'bahan_pangan_id' => 'required|exists:bahan_pangans,id',
            'mitra_id' => 'required|exists:mitras,id',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah_input' => 'required|numeric|min:0',
        ]);

        $detail = PoDetail::findOrFail($request->id);
        $detail->bahan_pangan_id = $request->bahan_pangan_id;
        $detail->mitra_id        = $request->mitra_id;
        $detail->harga_satuan    = $request->harga_satuan;
        $detail->jumlah_input    = $request->jumlah_input;
        $detail->total_harga     = $request->harga_satuan * $request->jumlah_input;
        $detail->save();

        // Update total PO
        $total = PoDetail::where('po_id', $detail->po_id)->sum('total_harga');
        $detail->po->update(['total_harga' => $total]);

        // untuk AJAX response
        return response()->json(['success' => true]);
    }


    public function cetak_detail($id)
    {
        // Ambil detail PO
        $detail = PoDetail::with('po')->findOrFail($id);

        // Ambil PO master dari detail
        $po = Po::with('poDetail.bahanPangan')->findOrFail($detail->po->id);
        $title = 'Cetak Po';
        return view('app.rab.po_cetak_detail', compact('po', 'title'));
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'po_detail_id' => 'required|exists:po_details,id',
            'jumlah_bayar' => 'required|numeric|min:1'
        ]);

        $detail = PoDetail::findOrFail($request->po_detail_id);

        // Tambah jumlah bayar (cicilan)
        $detail->jumlah_bayar = ($detail->jumlah_bayar ?? 0) + $request->jumlah_bayar;

        // Batasi supaya tidak lebih dari total tagihan
        if ($detail->jumlah_bayar >= $detail->total_harga) {
            $detail->jumlah_bayar = $detail->total_harga;
            $detail->status_bayar = 'PAID';
        } elseif ($detail->jumlah_bayar > 0) {
            $detail->status_bayar = 'PARTIAL';
        } else {
            $detail->status_bayar = 'UNPAID';
        }

        $detail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil disimpan',
            'detail_id' => $detail->id,
            'status_bayar' => $detail->status_bayar,
            'jumlah_bayar' => $detail->jumlah_bayar,
        ]);
    }

    public function bayarPO(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:po,id',
            'detail_id' => 'required|array',
            'detail_id.*' => 'exists:po_details,id',
            'jumlah_bayar' => 'required|array',
            'jumlah_bayar.*' => 'numeric|min:0',
        ]);

        foreach ($request->detail_id as $i => $detailId) {
            $detail = PoDetail::findOrFail($detailId);
            $bayar = (float) ($request->jumlah_bayar[$i] ?? 0);

            if ($bayar <= 0) continue;

            // hitung sisa
            $sisa = $detail->total_harga - ($detail->jumlah_bayar ?? 0);
            $bayarSekarang = min($sisa, $bayar);

            $detail->jumlah_bayar = ($detail->jumlah_bayar ?? 0) + $bayarSekarang;

            // update status
            if ($detail->jumlah_bayar >= $detail->total_harga) {
                $detail->status_bayar = 'PAID';
            } elseif ($detail->jumlah_bayar > 0) {
                $detail->status_bayar = 'PARTIAL';
            } else {
                $detail->status_bayar = 'UNPAID';
            }

            $detail->save();
        }

        return redirect()->back()->with('success', 'Pembayaran PO berhasil disimpan.');
    }

    public function cetakPO()
    {
        $title = 'Cetak PO';

        $pos = Po::with(['poDetail' => function ($q) {
            $q->where('jumlah_input', '>', 0)->with('bahanPangan');
        }])
            ->whereHas('poDetail', function ($q) {
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
