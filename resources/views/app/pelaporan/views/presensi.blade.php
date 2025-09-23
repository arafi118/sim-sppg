@extends('app.pelaporan.layout.base')
@section('content')
    <table border="1" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <td width="4%" align="center" rowspan="2">No</td>
                <td width="12%" align="center" rowspan="2">NIK</td>
                <td width="22%" align="center" rowspan="2">Nama Lengkap</td>
                <td align="center" colspan="31">Tanggal</td>
            </tr>
            <tr>
                @for ($i = 1; $i <= 31; $i++)
                    <td width="2%" align="center">{{ $i }}</td>
                @endfor
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                @php
                    $dataPresensi = [];
                    if (count($user->presensi) > 0) {
                        $dataPresensi = $user->presensi->pluck([], 'tanggal')->toArray();
                    }
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">{{ $user->nik }}</td>
                    <td style="padding-left: 4px;">{{ $user->nama }}</td>

                    @for ($i = 1; $i <= 31; $i++)
                        @php
                            $status = '-';
                            if (count($dataPresensi) > 0) {
                                $thn = $tahun;
                                $bln = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                $hari = str_pad($i, 2, '0', STR_PAD_LEFT);

                                $tanggal = $thn . '-' . $bln . '-' . $hari;
                                if (isset($dataPresensi[$tanggal])) {
                                    $presensi = $dataPresensi[$tanggal];

                                    if ($presensi['status'] == 'masuk') {
                                        $status = 'H';
                                    }
                                }
                            }
                        @endphp
                        <td align="center">{{ $status }}</td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
