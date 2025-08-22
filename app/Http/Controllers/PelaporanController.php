<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisLaporan;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class PelaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Pelaporan';
        $laporan = JenisLaporan::where([['file', '!=', '0']])->orderBy('urut', 'ASC')->get();
        return view('app.pelaporan.index', compact('title', 'laporan'));
    }

    public function preview(Request $request)
    {
        $laporan = $request->get('laporan');
        $data = $request->all();

        return $this->$laporan($data);
    }
    private function cover(array $data)
    {
        $data['judul'] = 'Laporan Keuangan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.cover', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);

        return $pdf->inline();
    }

    private function penggunaan_anggaran(array $data) 
    {
        $data['title'] = 'Laporan Penggunaan Anggaran';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.penggunaan_anggaran', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);

        return $pdf->inline();
    }


    private function surat_pernyataan(array $data) 
    {
        $data['title'] = 'Surat Pernyataan Tanggung Jawab';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.surat_pernyataan', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);

        return $pdf->inline();
    }
    private function tanda_terima(array $data) 
    {
        $data['title'] = 'Bukti Tanda Terima';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.tanda_terima', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);

        return $pdf->inline();
    }
     private function berita_acara(array $data) 
    {
        $data['title'] = 'Berita Acara';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.berita_acara', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);

        return $pdf->inline();
    }
         private function daftar_nominatif(array $data) 
    {
        $data['title'] = 'Berita Acara';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.daftar_nominatif', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);
        return $pdf->inline();
    }
}
