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

    // Buat title dinamis untuk PDF
    if ($jenis === 'Harian') {
        $title = "Rab Harian - " . Carbon::parse($tanggalParam[0])->translatedFormat('d F Y');
    } else {
        $title = "Rab periode - " . 
                 Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') . 
                 " s.d " . 
                 Carbon::parse($tanggalParam[1])->translatedFormat('d F Y');
    }

    $view = view('app.rab.pdf', compact('rancangan', 'tanggalParam', 'jenis'))->render();

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



}
