@extends('app.pelaporan.layout.base')

@section('content')
    <br>
    <div class="judul">KOP BADAN GIZI NASIONAL <br> FORMAT PEMERIKSAAN BAHAN MAKANAN </div>
    <div class="subjudul">No. ………………………… </div>

    <div class="ratakanankiri">
        <table border="0" width="100%">
            <tr>
                <td width="7%">Dari</td>
                <td width="2%">:</td>
                <td width="20%"></td>
                <td width="7%">Kepada</td>
                <td width="2%">:</td>
                <td width="20%"></td>
            </tr>
            <tr>
                <td width="7%">Alamat</td>
                <td width="2%">:</td>
                <td width="20%"></td>
                <td width="7%">Waktu</td>
                <td width="2%">:</td>
                <td width="20%"></td>
            </tr>
        </table>
    </div>
    <div>
        <table class="border-table">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Jenis Bahan Makanan</th>
                <th rowspan="2">Banyaknya (Angka)</th>
                <th rowspan="2">Satuan</th>
                <th colspan="2">Jumlah</th>
                <th colspan="2">Kondisi Bahan Makanan</th>
            </tr>
            <tr>
                <th>Sesuai</th>
                <th>Tidak</th>
                <th>Baik</th>
                <th>Rusak</th>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

    </div>
    <div>
        <table border="0" width="100%">
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">Jakarta, ...... 2025 </td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">Kepala Satuan Pelayanan Pemenuhan Gizi </td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">........................</td>
            </tr>
            <tr>
                <td width="10%" colspan="3"><br><br><br></td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">________________________ </td>
            </tr>
        </table>
    </div>
@endsection
