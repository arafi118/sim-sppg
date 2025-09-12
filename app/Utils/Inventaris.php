<?php

namespace App\Utils;

use App\Models\Inventaris as ModelsInventaris;
use App\Models\Rekening;

class Inventaris
{
  public static function nilaiBuku($tgl, $inv)
  {
    $tgl_beli = $inv->tgl_beli;

    $unit = (int) $inv->unit;
    $harga_perolehan = (float) $inv->harsat * $unit;
    $umur = max(1, (int) $inv->umur_ekonomis);

    if ($inv->kategori == 1 && $inv->jenis == 1) {
      return $harga_perolehan;
    }

    $penyusutan_per_bulan = $inv->harsat <= 0 ? 0 : round($harga_perolehan / $umur, 2);

    $ak_umur = self::bulan($tgl_beli, $tgl);
    $ak_susut = $penyusutan_per_bulan * $ak_umur;
    $nilai = $harga_perolehan - $ak_susut;

    return $nilai > 0 ? $nilai : 0;
  }

  public static function bulan($start, $end, $periode = 'bulan')
  {
    $batasan = date('t');
    $thn_awal    = substr($start, 0, 4);
    $bln_awal    = substr($start, 5, 2);
    $tgl_awal    = 01;

    if ($tgl_awal <= $batasan) {
      $tgl_awal = 01;
      if ($bln_awal == 1) {
        $thn_awal -= 1;
        $bln_awal = 12;
      } else {
        $bln_awal -= 1;
      }
    } else {
      $bln_awal = $bln_awal;
      $tgl_awal = $tgl_awal;
    }

    $start = "$thn_awal-$bln_awal-$tgl_awal";
    try {
      $d1 = new \DateTime($start);
      $d2 = new \DateTime($end);
    } catch (\Exception $e) {
      return 0;
    }

    $diff = $d1->diff($d2);

    switch ($periode) {
      case 'hari':
        return (int) $diff->days;
      case 'bulan':
        return ($diff->y * 12) + $diff->m;
      case 'tahun':
        return $diff->y + ($diff->m / 12);
      default:
        return 0;
    }
  }

  public static function penyusutan($tgl_kondisi, $kategori)
  {
    [$tahun, $bulan, $hari] = explode('-', $tgl_kondisi) + [null, null, null];
    $th_lalu = (int) $tahun - 1;

    $totals = [
      't_unit' => 0,
      't_harga' => 0,
      't_penyusutan' => 0,
      't_akum_susut' => 0,
      't_nilai_buku' => 0,
      'j_unit' => 0,
      'j_harga' => 0,
      'j_penyusutan' => 0,
      'j_akum_susut' => 0,
      'j_nilai_buku' => 0,
    ];

    $inventaris = ModelsInventaris::where([
      ['jenis', '1'],
      ['status', '!=', '0'],
      ['tgl_beli', '<=', $tgl_kondisi],
      ['tgl_beli', 'NOT LIKE', ''],
      ['harsat', '>', '0'],
      ['kategori', $kategori]
    ])->orderBy('tgl_beli', 'ASC')->get();

    foreach ($inventaris as $inv) {
      $harga = (float) $inv->harsat * (int) $inv->unit;

      if ($kategori == '1') {
        $totals['t_unit'] += $inv->unit;
        $totals['t_harga'] += $harga;
        $totals['t_nilai_buku'] += $harga;

        $nilai_buku = $harga;
        if (in_array($inv->status, ['Dijual', 'Hapus'])) {
          $nilai_buku = 0;
        }

        if (in_array($inv->status, ['Dijual', 'Hilang', 'Dihapus'])) {
          $totals['j_unit'] += $inv->unit;
          $totals['j_harga'] += $harga;
          $totals['j_nilai_buku'] += $harga;
        }

        continue;
      }

      $umur_ekonomis = max(1, (int) $inv->umur_ekonomis);
      $satuan_susut = $inv->harsat <= 0 ? 0 : round($harga / $umur_ekonomis, 2);

      if (!empty($inv->tgl_validasi) && $tgl_kondisi >= $inv->tgl_validasi && $inv->status !== 'Baik') {
        $umur = self::bulan($inv->tgl_beli, $inv->tgl_validasi);
      } else {
        $umur = self::bulan($inv->tgl_beli, $tgl_kondisi);
      }

      $nilai_buku = self::nilaiBuku($tgl_kondisi, $inv);
      if ($umur >= $umur_ekonomis) {
        $akum_susut = $harga;
        $nilai_buku = 0;
      } else {
        $akum_susut = round($satuan_susut * $umur, 2);
      }

      $pakai_lalu = self::bulan($inv->tgl_beli, $th_lalu . '-12-31');
      $umur_pakai = max(0, $umur - $pakai_lalu);
      $penyusutan = $satuan_susut * $umur_pakai;

      if (!empty($inv->tgl_validasi) && in_array($inv->status, ['Hilang', 'Dijual', 'Hapus']) && $tgl_kondisi >= $inv->tgl_validasi) {
        $akum_susut = $harga;
        $nilai_buku = 0;
        $penyusutan = 0;
        $umur_pakai = 0;
      }

      if (!empty($inv->tgl_validasi) && $inv->status == 'Rusak' && $tgl_kondisi >= $inv->tgl_validasi) {
        $akum_susut = $harga;
        $nilai_buku = 0;
        $penyusutan = 0;
        $umur_pakai = 0;
      }

      $totals['t_unit'] += $inv->unit;
      $totals['t_harga'] += $harga;
      $totals['t_penyusutan'] += $penyusutan;
      $totals['t_akum_susut'] += $akum_susut;
      $totals['t_nilai_buku'] += $nilai_buku;

      $tahun_validasi = !empty($inv->tgl_validasi) ? substr($inv->tgl_validasi, 0, 4) : null;
      if ($nilai_buku == 0 && $tahun_validasi !== null && $tahun_validasi < $tahun) {
        $totals['j_unit'] += $inv->unit;
        $totals['j_harga'] += $harga;
        $totals['j_penyusutan'] += $penyusutan;
        $totals['j_akum_susut'] += $akum_susut;
        $totals['j_nilai_buku'] += $nilai_buku;
      }
    }

    return $totals;
  }

  public static function saldoSusut($tanggal, $kode_akun)
  {
    $ymd = explode('-', $tanggal) + [null, null, null];
    $y = $ymd[0];
    $m = $ymd[1];

    $rekening = Rekening::where('kode_akun', $kode_akun)->with([
      'kom_saldo' => function ($query) use ($y, $m) {
        $query->where('tahun', $y)->where(function ($query) use ($m) {
          $query->where('bulan', $m)->orWhere('bulan', '0');
        });
      }
    ])->first();

    if (!$rekening) {
      return 0.0;
    }

    $saldo = 0.0;
    $awal_tahun = 0.0;

    foreach ($rekening->kom_saldo as $kom_saldo) {
      if ($kom_saldo->bulan == '0') {
        $awal_tahun += (float) $kom_saldo->kredit;
      } else {
        $saldo += (float) $kom_saldo->kredit;
      }
    }

    return floatval($awal_tahun + $saldo);
  }
}
