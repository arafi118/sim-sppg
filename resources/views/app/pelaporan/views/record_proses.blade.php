@extends('app.pelaporan.layout.base')
@section('content')
    <br>
    <div class="judul">Record Proses & Delivery</div>
    <div class="subjudul">&nbsp;</div>
    <table border="0" width="100%">
        <tr>
            <td width="10%">SPPG</td>
            <td width="1%">:</td>
            <td width="89%">{{ $profil->nama }}</td>
        </tr>
        <tr>
            <td width="10%">Alamat</td>
            <td width="1%">:</td>
            <td width="89%">{{ $profil->alamat }}</td>
        </tr>
    </table>
    <br>
    <table class="border-table">
        <tr>
            <th rowspan="2" width="2%">No</th>
            <th rowspan="2" width="40%">Tahapan</th>
            <th colspan="2" width="20%">Waktu Pelaksanaan</th>
            <th rowspan="2" width="35%">Pelaksana/Anggota</th>
        </tr>
        <tr>
            <th width="10%">Mulai</th>
            <th width="10%">Selesai</th>
        </tr>
        <tr>
            <td width="2%">1</td>
            <td width="40%">Pengumpulan Data</td>
            <td width="10%">-</td>
            <td width="10%">-</td>
            <td width="35%">-</td>
        </tr>
    </table>
@endsection
