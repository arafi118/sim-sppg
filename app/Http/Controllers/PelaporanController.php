<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisLaporan;
use App\Models\KelompokPemanfaat;
use App\Models\DataPemanfaat;
use App\Models\User;
use App\Utils\Tanggal;
use App\Models\Transaksi;
use App\Models\Profil;
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
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }

    private function penggunaan_anggaran(array $data)
    {
        $data['title'] = 'Laporan Penggunaan Anggaran';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.penggunaan_anggaran', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }

    private function surat_pernyataan(array $data)
    {
        $data['title'] = 'Surat Pernyataan Tanggung Jawab';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.surat_pernyataan', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }
    private function tanda_terima(array $data)
    {
        $data['title'] = 'Bukti Tanda Terima';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.tanda_terima', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }
private function jurnal_transaksi(array $data)
{
    $thn  = $data['tahun'];
    $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
    $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

    $tgl = $thn . '-' . $bln . '-' . $hari;

    $data['judul']     = 'Jurnal Transaksi';
    $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
    $data['tgl']       = Tanggal::tahun($tgl);
    $data['title']     = 'Jurnal Transaksi';
    if (!empty($data['bulan'])) { 
        $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
    }


    $data['transaksis'] = Transaksi::with(['rekeningDebit', 'rekeningKredit', 'user'])
    ->when(!empty($data['bulan']), function ($q) use ($thn, $bln) {
        $q->whereBetween('tanggal_transaksi', [
            "$thn-$bln-01",
            date('Y-m-t', strtotime("$thn-$bln-01"))
        ]);
    })
    ->when(!empty($data['hari']), function ($q) use ($thn, $bln, $hari) {
        $q->whereDate('tanggal_transaksi', "$thn-$bln-$hari");
    })
    ->orderBy('tanggal_transaksi', 'asc')
    ->get();


    $view = view('app.pelaporan.views.jurnal_transaksi', $data)->render();

    $pdf = PDF::loadHTML($view)->setOptions([
        'margin-top'    => 30,
        'margin-bottom' => 15,
        'margin-left'   => 25,
        'margin-right'  => 20,
        'header-html'   => view('app.pelaporan.layout.header', $data)->render(),
        'enable-local-file-access' => true,
    ]);

    return $pdf->inline();
}
    private function berita_acara(array $data)
    {
        $data['title'] = 'Berita Acara';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.berita_acara', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }
    private function daftar_nominatif(array $data)
    {
        $data['title'] = 'Daftar Nominatif';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.daftar_nominatif', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }

    private function proposal(array $data)
    {
        $data['title'] = 'Proposal';
        $data['tgl']   = now()->format('d-m-Y');
        $data['kelompokpemanfaat'] = KelompokPemanfaat::all();

        $view = view('app.pelaporan.views.proposal', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }
    private function pajak_belanja(array $data)
    {
        $data['title'] = 'Pajak Belanja';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.pajak_belanja', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);
        return $pdf->inline();
    }
    private function pks(array $data)
    {
        $data['title'] = 'Perjangan Kerja Sama';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.pks', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }
    private function bast(array $data)
    {
        $data['title'] = 'Berita Acara Serh Terima';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.bast', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);
        return $pdf->inline();
    }
    private function bukti_setor_pajak(array $data)
    {
        $data['title'] = 'Bukti Setor Pajak';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.bukti_setor_pajak', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 20,
            'margin-bottom' => 20,
            'margin-left'   => 25,
            'margin-right'  => 20,
        ]);
        return $pdf->inline();
    }
    private function laporan_pelaksanaan(array $data)
    {
        $data['title'] = 'Laporan Pelaksanaan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.laporan_pelaksanaan', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }
    private function pemeriksaan_bahan(array $data)
    {
        $data['title'] = 'Format Pemeriksaan Bahan Makanan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.pemeriksaan_bahan', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }
    private function pemeriksaan_makanan(array $data)
    {
        $data['title'] = 'Format Pemeriksaan Makanan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.pemeriksaan_makanan', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }
    private function nota_pesanan(array $data)
    {
        $data['title'] = 'Nota Pesanan Bahan Makanan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.nota_pesanan', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return $pdf->inline();
    }

    private function catatan_pengeluaran(array $data)
    {
        $data['title'] = 'Catatan pengeluaran';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.catatan_pengeluaran', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }
    private function kuitansi(array $data)
    {
        $data['title'] = 'Kuitansi';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.kuitansi', $data)->render();

        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);

        return $pdf->inline();
    }
    private function penerima_bantuan(array $data)
    {
        $data['title'] = 'Usulan Calon Penerima Bantuan';
        $data['tgl']   = now()->format('d-m-Y');

        $view = view('app.pelaporan.views.penerima_bantuan', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }

    private function calon_penerima_bantuan(array $data)
    {
        $data['title'] = 'Data Calon Penerima Bantuan';
        $data['tgl']   = now()->format('Y');
        $data['kelompokpemanfaat'] = KelompokPemanfaat::with('pemanfaat')->get();
        $data['profil'] = Profil::first();
        $data['ttd'] = User::where('level_id', 5)->first();

        $view = view('app.pelaporan.views.calon_penerima_bantuan', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }

    private function usulan_penerima_bantuan(array $data)
    {
        $data['title'] = 'Daftar Penerima Bantuan Posyandu';
        $data['tgl']   = now()->format('Y');
        $data['kelompokpemanfaat'] = KelompokPemanfaat::with(
            'pemanfaat',
            'pemanfaat.namaPemanfaat'
        )->get();
        $data['profil'] = Profil::first();
        $data['ttd'] = User::where('level_id', 5)->first();

        $view = view('app.pelaporan.views.usulan_penerima_bantuan', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }
    private function proposal_mingguan(array $data)
    {
        $data['title'] = 'Proposal Mingguan';
        $data['tgl']   = now()->format('Y');
        $data['kelompokpemanfaat'] = KelompokPemanfaat::with(
            'pemanfaat',
            'pemanfaat.namaPemanfaat'
        )->get();
        $data['profil'] = Profil::first();
        $data['ttd'] = User::where('level_id', 5)->first();

        $view = view('app.pelaporan.views.proposal_mingguan', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }
    private function record_proses(array $data)
    {
        $data['title'] = 'Record Proses & Delivery';
        $data['tgl']   = now()->format('Y');
        $data['kelompokpemanfaat'] = KelompokPemanfaat::with(
            'pemanfaat',
            'pemanfaat.namaPemanfaat'
        )->get();
        $data['profil'] = Profil::first();
        $data['ttd'] = User::where('level_id', 5)->first();

        $view = view('app.pelaporan.views.record_proses', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }
}
