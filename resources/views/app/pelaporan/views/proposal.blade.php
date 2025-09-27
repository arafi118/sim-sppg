@extends('app.pelaporan.layout.base')
@section('content')
    <br>
    <div class="judul">Proposal Pengajuan Penerima Pemanfaat</div>
    <div class="subjudul">Program MBG Tahun Anggaran </div>
    <div class="ratakanankiri">
        <table class="border-table">
            <tr>
                <th width="5%">No</th>
                <th width="50%">Nama</th>
                <th width="45%">Jumlah</th>
            </tr>
            @foreach ($kelompokpemanfaat as $pemanfaat)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ $pemanfaat->nama }}</td>
                    <td>..... orang</td>
                </tr>
            @endforeach
        </table>

        <table border="0" width="100%">
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">Wonosobo, ...... {{ $tahun }} </td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">Kepala Satuan Pelayanan Pemenuhan Gizi</td>
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
                <td width="10%">{{ $kepala->nama }} <br>________________________</td>
            </tr>
        </table>
    </div>
@endsection
