<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
use App\Models\Po;
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

        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->whereBetween('tanggal', $tanggalParam)
            ->orderBy('tanggal', 'ASC')
            ->get();

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

        // Buat title dinamis untuk PDF
        if ($jenis === 'Harian') {
            $title = "Rab Harian - " . Carbon::parse($tanggalParam[0])->translatedFormat('d F Y');
        } else {
            $title = "Rab periode - " .
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
            ->setOption('title', $title) // ini yang akan jadi judul PDF di header kiri
            ->inline('RAB.pdf');
    }
    public function PO(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $tanggal = $request->tanggal;

        if ($tanggal === '-') {
            $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal','ASC')->get();
        } else {
            $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
                ->whereDate('tanggal', $tanggal)
                ->orderBy('tanggal','ASC')->get();
        }

        $dataBahanPangan = [];
        foreach($rancangan as $r){
            $jumlahRancangan = $r->jumlah ?? 1;
            foreach($r->rancanganMenu as $rm){
                $menu = $rm->menu;
                if(!$menu) continue;
                foreach($menu->resep as $resep){
                    $bp = $resep->bahanPangan;
                    if(!$bp) continue;

                    $bpId = $bp->id;

                    if(!isset($dataBahanPangan[$bpId])){
                        $dataBahanPangan[$bpId] = [
                            'bahan_pangan_id' => $bpId,
                            'nama' => $bp->nama,
                            'satuan' => $bp->satuan,
                            'harga' => $bp->harga_jual ?? 0,
                            'jumlah' => 0
                        ];
                    }

                    $dataBahanPangan[$bpId]['jumlah'] += ($resep->gramasi ?? 0) * $jumlahRancangan;
                }
            }
        }

        uasort($dataBahanPangan, fn($a,$b) => strcmp($a['nama'],$b['nama']));

        return view('app.rab.po_tabel', compact('dataBahanPangan'));
    }

public function simpanPO(Request $request)
{
    // Ambil PO hari ini, buat baru jika belum ada
    $po = \App\Models\Po::firstOrCreate(
        ['tanggal' => now()->toDateString()], // kondisi
        ['total_harga' => 0, 'status_bayar' => 'unpaid'] // default jika baru
    );

    $jumlahs     = $request->input('jumlah_input');       // jumlah input user
    $hargaSatuan = $request->input('harga_satuan');      // harga satuan
    $kebutuhans  = $request->input('jumlah_kebutuhan');  // kebutuhan dari rancangan

    $totalKeseluruhan = 0;

    foreach ($jumlahs as $bpId => $jmlInput) {
        $harga      = $hargaSatuan[$bpId] ?? 0;
        $kebutuhan  = $kebutuhans[$bpId] ?? 0;
        $total      = $harga * $jmlInput;

        $totalKeseluruhan += $total;

        \App\Models\PoDetail::updateOrCreate(
            [
                'po_id'           => $po->id,
                'bahan_pangan_id' => $bpId
            ],
            [
                'harga_satuan' => $harga,
                'jumlah'       => $kebutuhan,   // kebutuhan dari rancangan
                'jumlah_input' => $jmlInput,    // input user
                'total_harga'  => $total,
                'status_bayar' => 'unpaid'
            ]
        );
    }

    // Update total_harga di PO master
    $po->update(['total_harga' => $totalKeseluruhan]);

    return response()->json([
        'message' => 'PO berhasil disimpan.',
        'po_id'   => $po->id,
        'total'   => $totalKeseluruhan
    ]);
}











    public function detailPO($id)
    {
        $title = 'Detail Po';
        $po = Po::with('poDetail.bahanPangan')->findOrFail($id);
        return view('app.rab.po_detail', compact('po','title'));
    }










}
