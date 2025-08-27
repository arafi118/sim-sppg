<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
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
    if (
        !$request->filled('tanggal') &&
        (!$request->filled('tanggal_awal') || !$request->filled('tanggal_akhir'))
    ) {
        return response()->json([
            'error' => 'Periode harus dipilih terlebih dahulu'
        ], 400);
    }

    // Tentukan tanggal
    if ($request->tanggal === '-') {
        $tanggalParam = [$request->tanggal_awal, $request->tanggal_akhir];
        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->whereBetween('tanggal', $tanggalParam)
            ->orderBy('tanggal', 'ASC')
            ->get();
    } else {
        $tanggalParam = [$request->tanggal];
        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->whereDate('tanggal', $request->tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();
    }

    $dataBahanPangan = [];
    foreach ($rancangan as $r) {
        $jumlahRancangan = $r->jumlah ?? 1;

        foreach ($r->rancanganMenu as $rm) {
            $menu = $rm->menu;
            if (!$menu) continue;

            $jumlahMenu = $rm->jumlah ?? 1;

            foreach ($menu->resep as $resep) {
                $bp = $resep->bahanPangan;
                if (!$bp) continue;

                $bpId = $bp->id;

                if (!isset($dataBahanPangan[$bpId])) {
                    $dataBahanPangan[$bpId] = [
                        'nama'   => $bp->nama,
                        'satuan' => $bp->satuan,
                        'harga'  => $bp->harga_jual ?? 0,
                        'jumlah' => 0,
                    ];
                }

                // Jumlah konsisten dengan RAB PDF
                $dataBahanPangan[$bpId]['jumlah'] += ($resep->gramasi ?? 0) * $jumlahRancangan;
            }
        }
    }

    uasort($dataBahanPangan, fn($a, $b) => strcmp($a['nama'], $b['nama']));

    // Bangun HTML tabel
    $html = '<table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Pangan</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Kebutuhan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>';

    if (empty($dataBahanPangan)) {
        $html .= '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
    } else {
        $grandTotal = 0;
        $no = 1;
        foreach ($dataBahanPangan as $b) {
            $total = $b['harga'] * $b['jumlah'];
            $grandTotal += $total;

            $html .= '<tr>
                <td class="center">'.$no.'</td>
                <td>'.$b['nama'].'</td>
                <td class="center">'.$b['satuan'].'</td>
                <td class="right">'.number_format($b['harga'], 0, ',', '.').'</td>
                <td class="right">'.number_format($b['jumlah'], 2, ',', '.').'</td>
                <td class="right">'.number_format($total, 0, ',', '.').'</td>
            </tr>';

            $no++;
        }

        $html .= '<tr>
            <th colspan="5" class="center">TOTAL</th>
            <th class="right">'.number_format($grandTotal, 0, ',', '.').'</th>
        </tr>';
    }

    $html .= '</tbody></table>';

    return $html;
}


}
