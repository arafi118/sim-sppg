<?php

namespace App\Utils;

use Carbon\Carbon;

class Tanggal
{
    public static function tglIndo($tanggal, $format = 'DD/MM/YYYY')
    {
        if ($tanggal != '') {
            $array_tgl = explode('-', $tanggal);
            $tahun = $array_tgl[0];
            $bulan = $array_tgl[1];
            $hari = $array_tgl[2];

            if (strlen($hari) > 0 && strlen($bulan) > 0) {
                $tanggal = $tahun . '-' . $bulan . '-' . $hari;
            } elseif (strlen($bulan) > 0) {
                $tanggal = $tahun . '-' . $bulan . '-01';
            } else {
                $tanggal = $tahun . '-12-31';
            }
            $tgl = new Carbon($tanggal);

            return $tgl->isoFormat($format);
        }

        return date('d/m/Y');
    }

    public static function tglNasional($tanggal)
    {
        $tgl = Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
        return $tgl;
    }

    public static function tglRomawi($tanggal)
    {
        $keuangan = new Keuangan;
        $array_tgl = explode('-', $tanggal);
        $tahun = $array_tgl[0];
        $bulan = $array_tgl[1];
        $hari = $array_tgl[2];

        $bulan_rom = $keuangan->romawi($bulan);
        $hari_rom = $keuangan->romawi($hari);

        return $bulan_rom . '/' . $tahun;
    }

    public static function tglLatin($tanggal)
    {
        $tgl = explode('-', $tanggal);

        return $tgl[2] . ' ' . self::namaBulan($tanggal) . ' ' . $tgl[0];
    }

    public static function tahun($tanggal)
    {
        $tgl = explode('-', $tanggal);
        return $tgl[0];
    }

    public static function bulan($tanggal)
    {
        $tgl = explode('-', $tanggal);
        return $tgl[1];
    }

    public static function namaBulan($tanggal)
    {
        $tgl = explode('-', $tanggal);
        $bln = $tgl[1];

        switch ($bln) {
            case '01': return 'Januari';
            case '02': return 'Februari';
            case '03': return 'Maret';
            case '04': return 'April';
            case '05': return 'Mei';
            case '06': return 'Juni';
            case '07': return 'Juli';
            case '08': return 'Agustus';
            case '09': return 'September';
            case '10': return 'Oktober';
            case '11': return 'November';
            case '12': return 'Desember';
            default: return '';
        }
    }

    public static function hari($tanggal)
    {
        $tgl = explode('-', $tanggal);
        return $tgl[2];
    }

    public static function namaHari($tanggal)
    {
        $hari = date('D', strtotime($tanggal));

        switch ($hari) {
            case 'Sun': return "Minggu";
            case 'Mon': return "Senin";
            case 'Tue': return "Selasa";
            case 'Wed': return "Rabu";
            case 'Thu': return "Kamis";
            case 'Fri': return "Jumat";
            case 'Sat': return "Sabtu";
            default: return "Tidak di ketahui";
        }
    }
}
