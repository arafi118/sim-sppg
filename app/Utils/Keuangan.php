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


}
