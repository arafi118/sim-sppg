<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisLaporan;
use App\Models\KelompokPemanfaat;
use App\Models\Rekening;
use App\Models\User;
use App\Models\AkunLevel1;
use App\Models\Calk;
use App\Models\MasterArusKas;
use App\Utils\Tanggal;
use App\Models\Transaksi;
use App\Models\Profil;
use App\Utils\Keuangan;
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

    public function subLaporan($file)
    {
        if ($file == 'buku_besar') {
            $rekening = Rekening::orderBy('kode_akun', 'ASC')->get();
            $sub_laporan = [];

            foreach ($rekening as $rek) {
                $sub_laporan[] = [
                    'value' => $rek->kode_akun,
                    'title' => $rek->kode_akun . '. ' . $rek->nama_akun
                ];
            }

            return view('app.pelaporan.partials.sub_laporan', [
                'type' => 'select',
                'sub_laporan' => $sub_laporan
            ]);
        } elseif ($file == 'calk') {
            $tahun = request()->get('tahun');
            $bulan = str_pad(request()->get('bulan'), 2, '0', STR_PAD_LEFT);

            $calk = Calk::where('tanggal', 'LIKE', "$tahun-$bulan%")->first();
            $keterangan = $calk ? $calk->catatan : '';

            return view('app.pelaporan.partials.sub_laporan', [
                'type' => 'textarea',
                'keterangan' => $keterangan
            ]);
        } else {
            $sub_laporan = [
                [
                    'value' => '',
                    'title' => '---'
                ]
            ];

            return view('app.pelaporan.partials.sub_laporan', [
                'type' => 'select',
                'sub_laporan' => $sub_laporan
            ]);
        }
    }

    public function preview(Request $request)
    {
        $laporan = $request->get('laporan');
        $data = $request->all();

        if ($laporan === 'buku_besar') {
            $data['kode_akun'] = $request->sub_laporan;
            $data['laporan']   = 'buku_besar'; 
            return $this->buku_besar($data);
        }
         if ($data['laporan'] == 'calk' && strlen($data['sub_laporan']) > 22) {
            Calk::where([
                 ['tanggal', 'LIKE', $data['tahun'] . '-' . $data['bulan'] . '%']
            ])->delete();

            Calk::create([
                 'tanggal' => $data['tahun'] . '-' . $data['bulan'] . '-01',
                'catatan' => $data['sub_laporan'],
            ]);
        }

        if (method_exists($this, $laporan)) {
            return $this->$laporan($data);
        }

        abort(404, 'Laporan tidak ditemukan');
    }

    private function cover(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl = $thn . '-' . $bln . '-' . $hari;

        $data['tahun']     = $thn;
        $data['judul']     = 'LAPORAN KEUANGAN';
        $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
        $data['tgl']       = Tanggal::tahun($tgl);
        $data['title']     = 'LAPORAN KEUANGAN';
        if (!empty($data['bulan'])) {
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        }

        $data['profil'] = Profil::first();

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

    private function arus_kas(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl_awal_tahun  = "{$thn}-01-01";
        $tgl_awal_bulan  = "{$thn}-{$bln}-01";
        $tgl_akhir_bulan = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int)$bln, (int)$thn);

        $data['judul'] = 'Laporan Arus Kas';
        
        $data['tgl_awal_bulan'] = $tgl_awal_bulan;
        $data['tgl_akhir_bulan'] = $tgl_akhir_bulan;

        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'bulan '  . ' ' . $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['tgl'] = $data['sub_judul'];
        $data['title'] = !empty($data['bulan'])
            ? 'Arus Kas (' . $namaBulan . ' ' . $thn . ')'
            : 'Arus Kas (Tahun ' . $thn . ')';

        // ambil arus kas dengan transaksi bulan berjalan
        $data['arus_kas'] = MasterArusKas::with([
            'child',
            'child.rek_debit.rek.transaksiDebit' => function ($q) use ($tgl_awal_bulan, $tgl_akhir_bulan) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
                ->where('rekening_kredit', 'like', '1.1.01%');
            },
            'child.rek_kredit.rek.transaksiKredit' => function ($q) use ($tgl_awal_bulan, $tgl_akhir_bulan) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
                ->where('rekening_debit', 'like', '1.1.01%');
            }
        ])->where('parent_id', 0)->get();

        // hitung saldo kas sampai akhir bulan sebelumnya
        $keuangan = new Keuangan;
        $tgl_saldo_lalu = date('Y-m-d', strtotime("-1 day", strtotime($tgl_awal_bulan)));
        $saldo_bulan_lalu = $keuangan->saldoKas($tgl_saldo_lalu);
        $data['saldo_bulan_lalu'] = $saldo_bulan_lalu;

        $view = view('app.pelaporan.views.arus_kas', $data)->render();

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

    private function neraca(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);

        $data['judul'] = 'Neraca';
        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'per ' . $lastDay . ' ' . $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['title'] = !empty($data['bulan']) ? $data['judul'] . ' (' . $namaBulan . ' ' . $thn . ')' : $data['judul'] . ' Tahun ' . $thn;

        $data['akun1'] = AkunLevel1::where('lev1', '<=', 3)
        ->with(['akun2.akun3.rek' => function($q) use ($tgl_awal, $tgl_akhir) {
            $q->whereHas('transaksiDebit', fn($q2) => $q2->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]))
            ->orWhereHas('transaksiKredit', fn($q2) => $q2->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]));
        }])
        ->orderBy('kode_akun', 'ASC')
        ->get();


        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;

        $view = view('app.pelaporan.views.neraca', $data)->render();

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

    private function neraca_saldo(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);

        $data['judul'] = 'Neraca ';

        $namaBulan = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay   = date('t', strtotime("{$thn}-{$bln}-01"));

        $data['sub_judul'] = !empty($data['bulan'])
            ? $namaBulan . ' ' . $thn
            : 'Tahun ' . $thn;

        $data['tgl'] = $data['sub_judul'];


        $data['title'] = !empty($data['bulan'])
            ? 'Neraca Saldo (' . $namaBulan . ' ' . $thn . ')'
            : 'Neraca Saldo (Tahun ' . $thn . ')';

        $data['rekening'] = Rekening::with([
            'transaksiDebit' => function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]);
            },
            'transaksiKredit' => function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]);
            }
        ])
            ->orderBy('kode_akun')
            ->get()
            ->transform(function ($rek) {
                $rek->total_debit  = $rek->transaksiDebit->sum('jumlah');
                $rek->total_kredit = $rek->transaksiKredit->sum('jumlah');
                return $rek;
            });
        $view = view('app.pelaporan.views.neraca_saldo', $data)->render();

        $pdf = PDF::loadHTML($view)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html'   => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ]);

        return $pdf->inline();
    }

    private function calk(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        // Tanggal awal & akhir periode
        $tgl_awal  = "{$thn}-01-01";
        $tgl_akhir = "{$thn}-{$bln}-" . cal_days_in_month(CAL_GREGORIAN, (int)$bln, (int)$thn);

        $data['judul'] = 'Calk';

        $namaBulanNormal = Tanggal::namaBulan("{$thn}-{$bln}-01"); // Contoh: Oktober
        $namaBulanCaps   = strtoupper($namaBulanNormal);            // Contoh: OKTOBER

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'BULAN ' . $namaBulanCaps . ' TAHUN ' . $thn
            : 'TAHUN ' . $thn;

        $data['title'] = !empty($data['bulan'])
            ? $data['judul'] . ' (' . $namaBulanNormal . ' ' . $thn . ')'
            : $data['judul'] . ' Tahun ' . $thn;

        $data['profil'] = Profil::first();

        // Ambil akun level 1–3 beserta rekening
        $data['akun1'] = AkunLevel1::where('lev1', '<=', 3)
            ->with(['akun2.akun3.rek'])
            ->orderBy('kode_akun', 'ASC')
            ->get();

        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;

        // ✅ Ambil catatan CALK berdasarkan tahun dan bulan
        $calk = Calk::where('tanggal', 'LIKE', "{$thn}-{$bln}%")->first();
        $data['catatan'] = $calk ? $calk->catatan : '';

        // Render view CALK
        $view = view('app.pelaporan.views.calk', $data)->render();

        // Generate PDF
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

    private function laba_rugi(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = $data['bulan'] ?? null;
        $hari = $data['hari'] ?? null;

        $tgl = $thn
            . ($bln ? '-' . $bln : '-12')
            . ($hari ? '-' . $hari : '-' . date('t', strtotime("$thn-" . ($bln ?? '12') . "-01")));

        $keuangan = new Keuangan();
        $lr = $keuangan->listLabaRugi($tgl);

        $data['judul'] = 'Laporan Laba Rugi';
        $namaBulanAkhir = Tanggal::namaBulan("{$thn}-{$bln}-01");
        $lastDay        = date('t', strtotime("{$thn}-{$bln}-01"));

        // Awal selalu 01 Januari
        $awal = '01 Januari ' . $thn;
        $akhir = $lastDay . ' ' . $namaBulanAkhir . ' ' . $thn;

        $data['sub_judul'] = !empty($data['bulan'])
            ? 'PERIODE ' . $awal . ' S.D. ' . $akhir
            : 'TAHUN ' . $thn;



        $data['pendapatan'] = $lr['pendapatan'];
        $data['beban']      = $lr['beban'];
        $data['bp']         = $lr['bp'];
        $data['pen']        = $lr['pen'];
        $data['pendl']      = $lr['pendl'];
        $data['beb']        = $lr['beb'];
        $data['ph']         = $lr['ph'];

        $data['title'] = 'Laba Rugi';

        // Render PDF
        $view = view('app.pelaporan.views.laba_rugi', $data)->render();
        $pdf = PDF::loadHTML($view)->setOptions([
            'margin-top'    => 30,
            'margin-bottom' => 15,
            'margin-left'   => 25,
            'margin-right'  => 20,
            'header-html' => view('app.pelaporan.layout.header', $data)->render(),
            'enable-local-file-access' => true,
        ]);
        return PDF::loadHTML($view)->inline();
    }

    private function buku_besar(array $data)
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal_tahun  = "$thn-01-01";
        $tgl_awal_bulan  = "$thn-$bln-01";
        $tgl_akhir_bulan = "$thn-$bln-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);


        // Ambil rekening yang dipilih
        $rek = Rekening::where('kode_akun', $data['kode_akun'])->first();
        if (!$rek) {
            return abort(404, 'Rekening tidak ditemukan!');
        }
        $data['rek'] = $rek;
        $data['judul'] = "Buku Besar " . ($rek->kode_akun ?? '-') . " (" . Tanggal::namaBulan($tgl_awal_bulan) . " $thn)";

        // Saldo Awal Tahun
        $saldo_awal = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->where('tanggal_transaksi', '<', $tgl_awal_tahun)
            ->get()
            ->reduce(fn($carry, $trx) => $carry + (
                $trx->rekening_debit == $rek->id
                    ? ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah)
                    : ($rek->jenis_mutasi == 'debet' ? -$trx->jumlah : $trx->jumlah)
            ), 0);
        $data['saldo_awal'] = $saldo_awal;

        // Kumulatif s/d Bulan Lalu
        $transaksi_bulan_lalu = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, date('Y-m-d', strtotime("$tgl_awal_bulan -1 day"))])
            ->get();

        $komulatif_bulan_lalu = $transaksi_bulan_lalu->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
                $carry['saldo'] += ($rek->jenis_mutasi == 'debet' ? $trx->jumlah : -$trx->jumlah);
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
                $carry['saldo'] += ($rek->jenis_mutasi == 'debet' ? -$trx->jumlah : $trx->jumlah);
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0, 'saldo' => $saldo_awal]);

        $data['komulatif_bulan_lalu_debit']  = $komulatif_bulan_lalu['debit'];
        $data['komulatif_bulan_lalu_kredit'] = $komulatif_bulan_lalu['kredit'];
        $data['komulatif_bulan_lalu_saldo']  = $komulatif_bulan_lalu['saldo'];

        // Transaksi Bulan Ini
        $transaksi_bulan_ini = Transaksi::with('user')
            ->where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_bulan, $tgl_akhir_bulan])
            ->orderBy('tanggal_transaksi')
            ->get();
        $data['transaksi'] = $transaksi_bulan_ini;

        $total_bulan_ini = $transaksi_bulan_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0]);
        $data['total_bulan_ini'] = $total_bulan_ini;

        // Total s/d Bulan Ini (Jan - Bulan Ini)
        $data['total_sampai_bulan_ini'] = [
            'debit'  => $komulatif_bulan_lalu['debit'] + $total_bulan_ini['debit'],
            'kredit' => $komulatif_bulan_lalu['kredit'] + $total_bulan_ini['kredit'],
            'saldo'  => $komulatif_bulan_lalu['saldo']
                    + ($rek->jenis_mutasi == 'debet'
                            ? $total_bulan_ini['debit'] - $total_bulan_ini['kredit']
                            : $total_bulan_ini['kredit'] - $total_bulan_ini['debit']),
        ];

        // Total Kumulatif Tahun (sampai Desember)
        $transaksi_tahun_ini = Transaksi::where(fn($q) => $q
                ->where('rekening_debit', $rek->id)
                ->orWhere('rekening_kredit', $rek->id))
            ->whereBetween('tanggal_transaksi', [$tgl_awal_tahun, "$thn-12-31"])
            ->get();

        $total_tahun_ini = $transaksi_tahun_ini->reduce(function ($carry, $trx) use ($rek) {
            if ($trx->rekening_debit == $rek->id) {
                $carry['debit'] += $trx->jumlah;
            } elseif ($trx->rekening_kredit == $rek->id) {
                $carry['kredit'] += $trx->jumlah;
            }
            return $carry;
        }, ['debit' => 0, 'kredit' => 0]);
        $data['total_tahun_ini'] = $total_tahun_ini;

        // Sub Judul + tanggal
        $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl_awal_bulan) . ' ' . $thn;
        $data['tgl_awal_bulan']  = $tgl_awal_bulan;
        $data['tgl_akhir_bulan'] = $tgl_akhir_bulan;
        $data['tahun'] = $thn;
        $data['bulan'] = $bln;

        // Render ke PDF
        $view = view('app.pelaporan.views.buku_besar', $data)->render();

        return Pdf::loadHTML($view)
            ->setOptions([
                'margin-top' => 30,
                'margin-bottom' => 15,
                'margin-left' => 25,
                'margin-right' => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
            ])
            ->inline('buku_besar.pdf');
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
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $hari = str_pad($data['hari'], 2, '0', STR_PAD_LEFT);

        $tgl = $thn . '-' . $bln . '-' . $hari;

        $data['tahun']     = $thn;
        $data['judul']     = 'LAPORAN KEUANGAN';
        $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
        $data['tgl']       = Tanggal::tahun($tgl);
        $data['title']     = 'LAPORAN KEUANGAN';
        if (!empty($data['bulan'])) {
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl']       = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        }

        $data['profil'] = Profil::first();
        $data['title'] = 'Proposal';

        $data['kepala'] = User::where('level_id', '5')->first();

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

    private function presensi(array $data)
    {
        $data['users'] = User::whereNotIn('level_id', ['1', '7'])->with([
            'presensi' => function ($q) use ($data) {
                $q->whereMonth('tanggal', $data['bulan'])
                    ->whereYear('tanggal', $data['tahun']);
            }
        ])->get();

        $data['title'] = 'Laporan Presensi';
        $view = view('app.pelaporan.views.presensi', $data)->render();
        $pdf = PDF::loadHTML($view)
            ->setOptions([
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'header-html' => view('app.pelaporan.layout.header', $data)->render(),
                'enable-local-file-access' => true,
                'orientation' => 'landscape',
                'page-size' => 'A4',
            ]);

        return $pdf->inline();
    }
}
