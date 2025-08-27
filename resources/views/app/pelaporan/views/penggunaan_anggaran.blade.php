@extends('app.pelaporan.layout.base')
@section('content')
    <br>
    <div class="judul">
        LAPORAN PENGGUNAAN ANGGARAN <br>
        Nomor : 01/LPA/IV/2025
    </div>
    <div class="subjudul">&nbsp;</div>

    <div class="ratakanankiri">
        Periode 17 Februari 2025 - 28 Februari 2025
    </div>
    <div>
        <p>Yang bertanda tangan di bawah ini </p>
        <table border="0" width="100%">
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Nama</td>
                <td width="1%">:</td>
                <td>Rakha Pratama / Niki</td>
            </tr>
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Jabatan</td>
                <td width="1%">:</td>
                <td>Kepala Satuan Pelayanan Pemenuhan Gizi / Ketua Yayasan</td>
            </tr>
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Yayasan/SPPG</td>
                <td width="1%">:</td>
                <td>Persero / Khusus Kota Depok Tapos 1</td>
            </tr>
        </table>
    </div>
    <div class="ratakanankiri">Dengan ini menyampaikan laporan penggunaan dana sebagai berikut:</div>
    <div class="ratakanankiri">
        I. Rincian Keuangan
        <table class="border-table">
            <thead>
                <tr>
                    <th>Uraian</th>
                    <th>Dana Diajukan (Rp)</th>
                    <th>Dana Terealisasi (Rp)</th>
                    <th>Sisa Dana (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bahan Baku</td>
                    <td align="right">69.967.500</td>
                    <td align="right">50.000.000</td>
                    <td align="right">19.976.000</td>
                </tr>
                <tr>
                    <td>Operasional</td>
                    <td align="right">89.991.000</td>
                    <td align="right">84.259.500</td>
                    <td align="right">5.731.500</td>
                </tr>
                <tr>
                    <td>Sewa</td>
                    <td align="right">69.994.000</td>
                    <td align="right">69.994.000</td>
                    <td align="right">-</td>
                </tr>
                <tr>
                    <td><b>Total</b></td>
                    <td align="right"><b>199.961.000</b></td>
                    <td align="right"><b>204.253.500</b></td>
                    <td align="right"><b>25.707.500</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="ratakanankiri">II. Keterangan
        <p class="ratakanankiri">
            Dana yang telah digunakan sesuai dengan kebutuhan kegiatan yang telah direncanakan, dengan rincian sebagai
            berikut:
        </p>
        <table border="0" width="100%">
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Bahan Baku</td>
                <td width="1%">:</td>
                <td width="75%">Pengadaan bahan baku utama untuk pelaksanaan kegiatan.</td>
            </tr>
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Operasional</td>
                <td width="1%">:</td>
                <td width="75%">Biaya transportasi, ATK, konsumsi, dan keperluan teknis lainnya.</td>
            </tr>
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">Sewa</td>
                <td width="1%">:</td>
                <td width="75%">Bangunan, mobil, dll.</td>
            </tr>
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="15%">No. Rekening / Virtual Akun</td>
                <td width="1%">:</td>
                <td width="75%">920190901</td>
            </tr>
        </table>
    </div>
    <div class="ratakanankiri">
        Sisa dana sebesar Rp. 25.707.500,00 akan dialihkan ke periode selanjutnya.
        Penghasilan dana ini bertujuan untuk mendukung kegiatan yang telah direncanakan pada periode berikutnya.
    </div>
    <table width="100%" border="0">
        <tr>
            <td width="100%" colspan="3" align="right">Depok, 23 Maret 2025 </td>
        </tr>
        <tr>
            <td width="40%" align="center" valign="top">
                Pihak Pertama <br>
                Yayasan Perjuangan Untuk <br>
                Kesejahteraan Rakyat
            </td>
            <td width="20%"></td>
            <td width="40%" align="center" valign="top">
                Pihak Kedua <br>
                Staf Akuntansi SPPG Kota Depok Tapos 1
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br><br></td>
        </tr>
        <tr>
            <td width="40%" align="center" valign="top">
                <u>.................................</u><br>
                Ketua / Mewakili
            </td>
            <td width="20%"></td>
            <td width="40%" align="center" valign="top">
                <u>Bobby Mandala Putra, S.Ak</u>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                Mengetahui, <br>
                Kepala SPPG Kota Depok Tapos 1
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br><br><br></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <u>Rakha Pratama, S.Pd., S.Ag., M.Han</u>
            </td>
        </tr>
    </table>
@endsection
