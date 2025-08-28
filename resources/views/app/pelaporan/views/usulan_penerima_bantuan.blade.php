@extends('app.pelaporan.layout.base')
@section('content')
    <br>
    <div class="judul">Daftar Usulan Calon Penerima bantuan Posyandu</div>
    <div class="subjudul"> Program MBG Tahun Anggaran {{ $tgl }} </div>
    <div class="ratakanankiri">
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
    </div><br>
    <table class="angka ratakanankiri">
        @foreach ($kelompokpemanfaat as $kp)
            <tr>
                <td style="vertical-align: top;">
                    {{ $loop->iteration }}.
                </td>
                <td>
                    <span>Rekapitulasi Daftar Usulan Penerima bantuan kategori ({{ $kp->nama }}) pada
                        ({{ $kp->pemanfaat->first()->nama_lembaga }})
                    </span>
                    @if (str_contains(strtolower($kp->nama), 'lah'))
                        <table class="border-table">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nomor Induk Siswa Nasional (NISN)</th>
                                <th rowspan="2">Nama Siswa </th>
                                <th rowspan="2">Umur</th>
                                <th colspan="2">Jenis Kelamin</th>
                                <th rowspan="2">Kelas</th>
                                <th rowspan="2">Nama Orang Tua/wali</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>L</th>
                                <th>P</th>
                            </tr>
                            @foreach ($kp->pemanfaat as $pemanfaat)
                                @foreach ($pemanfaat->namaPemanfaat as $nP)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $nP->nik ?? '-' }}</td>
                                        <td>{{ $nP->nama ?? '-' }}</td>
                                        <td>{{ $nP->umur ?? '-' }}</td>
                                        <td>{{ $nP->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $nP->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $pemanfaat->kelas ?? '-' }}</td>
                                        <td>{{ $pemanfaat->nama_pj ?? '-' }}</td>
                                        <td>{{ $pemanfaat->nama_lembaga ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    @elseif (str_contains(strtolower($kp->nama), 'mil'))
                        <table class="border-table">
                            <tr>
                                <th>No</th>
                                <th>Nomor Induk Kependudukan (NIK) </th>
                                <th>Nama Calon</th>
                                <th>Umur</th>
                                <th>Keterangan (Nama Posyandu) </th>
                            </tr>

                            @foreach ($kp->pemanfaat as $pemanfaat)
                                @foreach ($pemanfaat->namaPemanfaat as $nP)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $nP->nik ?? '-' }}</td>
                                        <td>{{ $nP->nama ?? '-' }}</td>
                                        <td>{{ $nP->umur ?? '-' }}</td>
                                        <td>{{ $pemanfaat->nama_lembaga ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    @elseif (str_contains(strtolower($kp->nama), 'lita'))
                        <table class="border-table">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nomor Induk Kependudukan (NIK) </th>
                                <th rowspan="2">Nama Calon </th>
                                <th rowspan="2">Umur </th>
                                <th colspan="2">Jenis Kelamin </th>
                                <th rowspan="2">Nama Orang Tua/wali</th>
                                <th rowspan="2">Keterangan (Nama Posyandu)</th>
                            </tr>
                            <tr>
                                <th>L</th>
                                <th>P</th>
                            </tr>
                            @foreach ($kp->pemanfaat as $pemanfaat)
                                @foreach ($pemanfaat->namaPemanfaat as $nP)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $nP->nik ?? '-' }}</td>
                                        <td>{{ $nP->nama ?? '-' }}</td>
                                        <td>{{ $nP->umur ?? '-' }}</td>
                                        <td>{{ $nP->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $nP->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $pemanfaat->nama_pj ?? '-' }}</td>
                                        <td>{{ $pemanfaat->nama_lembaga ?? '-' }}</td>
                                    </tr>
                                @endforeach
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
