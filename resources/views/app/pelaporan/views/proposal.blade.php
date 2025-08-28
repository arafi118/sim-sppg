@extends('app.pelaporan.layout.base')
@section('content')
    <br>
    <div class="judul">Proposal Pengajuan Penerima Pemanfaat</div>
    <div class="subjudul">Program MBG Tahun Anggaran 2025</div>
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
    </div>
@endsection
