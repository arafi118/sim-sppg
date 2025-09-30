<?php

namespace App\Utils;

use App\Models\AkunLevel2;
use App\Models\Rekening;
use App\Models\Saldo;
use App\Models\Transaksi;
use DB;

class Keuangan
{
    public static function hitungSaldo($lev3, $tgl_awal = null, $tgl_akhir = null)
    {
        $saldo = 0;

        foreach ($lev3->rek as $rekening) {
            $total_debit = $rekening->transaksiDebit()
                ->when($tgl_awal && $tgl_akhir, fn($q) => $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]))
                ->sum('jumlah');

            $total_kredit = $rekening->transaksiKredit()
                ->when($tgl_awal && $tgl_akhir, fn($q) => $q->whereBetween('tanggal_transaksi', [$tgl_awal, $tgl_akhir]))
                ->sum('jumlah');

            $saldo_rekening = $rekening->jenis_mutasi === 'debet'
                ? $total_debit - $total_kredit
                : $total_kredit - $total_debit;

            $saldo += $saldo_rekening;
        }

        return $saldo;
    }

    public static function formatSaldo($nilai)
    {
        $formatted = number_format(abs($nilai), 2, ',', '.');
        return $nilai < 0 ? '(' . $formatted . ')' : $formatted;
    }

    public function listLabaRugi(string $tgl): array
    {
        // Pendapatan (4.1.%)
        $pendapatan = Rekening::where('kode_akun', 'LIKE', '4.1.%')
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Beban (5.1.% dan 5.2.% kecuali 5.2.01.01)
        $beban = Rekening::where(function ($q) {
                $q->where('kode_akun', 'LIKE', '5.1.%')
                  ->orWhere(function ($q2) {
                      $q2->where('kode_akun', 'LIKE', '5.2.%')
                         ->where('kode_akun', '!=', '5.2.01.01');
                  });
            })
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Beban penyusutan (5.2.01.01)
        $bp = Rekening::where('kode_akun', '5.2.01.01')
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Pendapatan lain-lain (4.2.% + 4.3.% kecuali 4.3.01.0x)
        $pen = Rekening::where(function ($q) {
                $q->where('kode_akun', 'LIKE', '4.2.%')
                  ->orWhere(function ($q2) {
                      $q2->where('kode_akun', 'LIKE', '4.3.%')
                         ->whereNotIn('kode_akun', [
                             '4.3.01.01',
                             '4.3.01.02',
                             '4.3.01.03',
                         ]);
                  });
            })
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Pendapatan denda / lain-lain (4.3.01.0x)
        $pendl = Rekening::whereIn('kode_akun', [
                '4.3.01.01',
                '4.3.01.02',
                '4.3.01.03'
            ])
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Beban lain-lain (5.3.% kecuali 5.4.01.01)
        $beb = Rekening::where('kode_akun', 'LIKE', '5.3.%')
            ->where('kode_akun', '!=', '5.4.01.01')
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        // Penyusutan & hutang (5.4.%)
        $ph = Rekening::where('kode_akun', 'LIKE', '5.4.%')
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($rek) use ($tgl) {
                $rek->saldo = $this->hitungLabarugi($rek, $tgl);
                return $rek;
            });

        return [
            'pendapatan' => $pendapatan,
            'beban'      => $beban,
            'bp'         => $bp,
            'pen'        => $pen,
            'pendl'      => $pendl,
            'beb'        => $beb,
            'ph'         => $ph,
        ];
    }

    private function hitungLabarugi(Rekening $rek, string $tgl): float
    {
        $debit = $rek->transaksiDebit()
            ->where('tanggal_transaksi', '<=', $tgl)
            ->sum('jumlah'); 

        $kredit = $rek->transaksiKredit()
            ->where('tanggal_transaksi', '<=', $tgl)
            ->sum('jumlah');  

        // Sesuaikan apakah akun normal debit/kredit
        return $rek->normal == 'D' ? $debit - $kredit : $kredit - $debit;
    }
    
    public function saldoKas($tgl_akhir)
    {
        $tanggal = explode('-', $tgl_akhir);
        $thn = $tanggal[0];
        $bln = $tanggal[1];

        $saldo = 0;

        // Ambil rekening kas 1.1.01 dan 1.1.02
        $rekening = Rekening::where('kode_akun', 'like', '1.1.01%')
                    ->orWhere('kode_akun', 'like', '1.1.02%')
                    ->with(['transaksiDebit' => function($q) use ($tgl_akhir) {
                        $q->where('tanggal_transaksi', '<=', $tgl_akhir);
                    }, 'transaksiKredit' => function($q) use ($tgl_akhir) {
                        $q->where('tanggal_transaksi', '<=', $tgl_akhir);
                    }])
                    ->get();

        foreach ($rekening as $rek) {
            $total_debit = $rek->transaksiDebit->sum('jumlah');
            $total_kredit = $rek->transaksiKredit->sum('jumlah');

            if ($rek->lev1 < 2) {
                $saldo += $total_debit - $total_kredit;
            } else {
                $saldo += $total_kredit - $total_debit;
            }
        }

        return $saldo;
    }

    public function romawi($angka)
    {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X'];
        return $map[$angka] ?? $angka;
    }

    public static function bukuBesar(array $data): array
    {
        $thn  = $data['tahun'];
        $bln  = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);

        $tgl_awal_tahun  = "$thn-01-01";
        $tgl_awal_bulan  = "$thn-$bln-01";
        $tgl_akhir_bulan = "$thn-$bln-" . cal_days_in_month(CAL_GREGORIAN, (int) $bln, (int) $thn);

        // Rekening
        $rek = Rekening::where('kode_akun', $data['kode_akun'])->first();
        if (!$rek) {
            throw new \Exception("Rekening tidak ditemukan!");
        }
        $data['rek'] = $rek;

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

        // ---------------------------
        // Transaksi Bulan Ini
        // ---------------------------
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

        // Total s/d Bulan Ini
        $data['total_sampai_bulan_ini'] = [
            'debit'  => $komulatif_bulan_lalu['debit'] + $total_bulan_ini['debit'],
            'kredit' => $komulatif_bulan_lalu['kredit'] + $total_bulan_ini['kredit'],
            'saldo'  => $komulatif_bulan_lalu['saldo']
                    + ($rek->jenis_mutasi == 'debet'
                            ? $total_bulan_ini['debit'] - $total_bulan_ini['kredit']
                            : $total_bulan_ini['kredit'] - $total_bulan_ini['debit']),
        ];

        // Total Tahun Ini (Jan-Des)
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

       
        $data['sub_judul']       = 'Bulan ' . Tanggal::namaBulan($tgl_awal_bulan) . ' ' . $thn;
        $data['tgl_awal_bulan']  = $tgl_awal_bulan;
        $data['tgl_akhir_bulan'] = $tgl_akhir_bulan;
        $data['tahun'] = $thn;
        $data['bulan'] = $bln;

        return $data;
    }


}
