@extends('app.pelaporan.layout.base')
@section('content')
<br>
<div class="judul">Data Usulan Calon Penerima bantuan</div>
<div class="subjudul"> Program MBG Tahun Anggaran {{ $tgl }} </div>
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
</table><br>
<table class="angka ratakanankiri">
    @foreach ($kelompokpemanfaat as $kp)
    <tr>
        <td style="vertical-align: top;">
            {{ $loop->iteration }}.
        </td>
        <td>
            <span>Rekapitulasi Data Usulan Calon Penerima bantuan ({{ $kp->nama }})</span>
            @if (str_contains(strtolower($kp->nama), 'lah'))
            <table class="border-table">
                <tr>
                    <th>No</th>
                    <th>Nama Posyandu </th>
                    <th>Nama Kepala Kader</th>
                    <th>No. HP Kepala Kader</th>
                    <th>Alamat Posyandu </th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
                @foreach ($kp->pemanfaat as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama_lembaga }}</td>
                    <td>{{ $p->nama_pj }}</td>
                    <td>{{ $p->telpon_pj }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->jumlah_pemanfaat }}</td>
                    <td></td>
                </tr>
                @endforeach
            </table>
            @elseif (str_contains(strtolower($kp->nama), 'mil'))
            <table class="border-table">
                <tr>
                    <th>No</th>
                    <th>Nama Posyandu </th>
                    <th>Nama Kepala Kader</th>
                    <th>No. HP Kepala Kader</th>
                    <th>Alamat Posyandu </th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>

                @foreach ($kp->pemanfaat as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama_lembaga }}</td>
                    <td>{{ $p->nama_pj }}</td>
                    <td>{{ $p->telpon_pj }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->jumlah_pemanfaat }}</td>
                    <td>
                        <table></table>
                    </td>
                </tr>
                @endforeach
            </table>
            @elseif (str_contains(strtolower($kp->nama), 'lita'))
            <table class="border-table">
                <tr>
                    <th>No</th>
                    <th>Nama Posyandu </th>
                    <th>Nama Kepala Kader</th>
                    <th>No. HP Kepala Kader</th>
                    <th>Alamat Posyandu </th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
                @foreach ($kp->pemanfaat as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama_lembaga }}</td>
                    <td>{{ $p->nama_pj }}</td>
                    <td>{{ $p->telpon_pj }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->jumlah_pemanfaat }}</td>
                    <td></td>
                </tr>
                @endforeach
            </table>
            @else
            <div class="text-center">
                Data belum tersedia
            </div>
            @endif
        </td>
    </tr>
    @endforeach
</table>
<br>
<table width="100%" border="0">
    <tr>
        <td width="40%">&nbsp;</td>
        <td width="20%">&nbsp;</td>
        <td width="40%" align="center" valign="top">
            ……., Januari 2025</td>
    </tr>
    <tr>
        <td width="40%">&nbsp;</td>
        <td width="20%">&nbsp;</td>
        <td width="40%" align="center" valign="top">
            Kepala SPPG ......... </td>
    </tr>
    <tr>
        <td colspan="3"><br><br><br></td>
    </tr>
    <tr>
        <td width="40%">&nbsp;</td>
        <td width="20%">&nbsp;</td>
        <td width="40%" align="center" valign="top">
            <u>{{ $ttd->nama }}</u>
        </td>
    </tr>
</table>
@endsection
