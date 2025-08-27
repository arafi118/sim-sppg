<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
use App\Models\RancangMenu;
use App\Models\Menu;
use App\Models\Resep;
use App\Models\BahanPangan;
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

    $tanggalParam = ($tanggalParam[0] === '-') 
        ? [$request->tanggal_awal, $request->tanggal_akhir]
        : (count($tanggalParam) === 1 ? [$tanggalParam[0], $tanggalParam[0]] : $tanggalParam);

    if (count($tanggalParam) !== 2 || !$tanggalParam[0] || !$tanggalParam[1]) {
        return back()->with('error', 'Tanggal tidak valid!');
    }

    $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
        ->whereBetween('tanggal', $tanggalParam)
        ->orderBy('tanggal', 'ASC')
        ->get();

    $jenis = ($tanggalParam[0] === $tanggalParam[1]) ? 'Harian' : 'Periode';

    // Gabungkan menu berdasarkan nama dan jumlahkan bahan
    $menuGabungan = [];
    foreach ($rancangan as $r) {
        foreach ($r->rancanganMenu as $rm) {
            $menuName = $rm->menu->nama ?? '-';
            if (!isset($menuGabungan[$menuName])) {
                $menuGabungan[$menuName] = [
                    'menu' => $rm->menu,
                    'bahan' => [],
                ];
            }

            foreach ($rm->menu->resep as $resep) {
                $bpId = $resep->bahanPangan->id ?? null;
                if ($bpId) {
                    if (!isset($menuGabungan[$menuName]['bahan'][$bpId])) {
                        $menuGabungan[$menuName]['bahan'][$bpId] = [
                            'bahanPangan' => $resep->bahanPangan,
                            'gramasi' => 0,
                        ];
                    }
                    $menuGabungan[$menuName]['bahan'][$bpId]['gramasi'] += $resep->gramasi ?? 0;
                }
            }
        }
    }

    // Judul PDF
    if ($jenis === 'Harian') {
        $title = "Rab Harian - " . Carbon::parse($tanggalParam[0])->translatedFormat('d F Y');
    } else {
        $title = "Rab periode - " . 
                 Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') . 
                 " s.d " . 
                 Carbon::parse($tanggalParam[1])->translatedFormat('d F Y');
    }

    return PDF::loadView('app.rab.pdf', compact('menuGabungan', 'tanggalParam', 'jenis', 'title','rancangan'))
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
