@extends('app.pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px; font-weight:bold; margin: 0; line-height: 1.2;">
                    {{ strtoupper($judul) }}
                </div>
                <div style="font-size: 16px; font-weight:bold; margin: 0; line-height: 1.2;">
                    {{ strtoupper($sub_judul) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>

        <tr style="background:#000; color:#fff; font-weight:600;">
            <td width="10%" style="padding:4px;">Kode</td>
            <td width="70%" style="padding:4px;">Nama Akun</td>
            <td width="20%" style="padding:4px;" align="right">Saldo</td>
        </tr>

        @php
            $i = 0;
            function formatSaldo($nilai)
            {
                $formatted = number_format(abs($nilai), 2, ',', '.');
                return $nilai < 0 ? '(' . $formatted . ')' : $formatted;
            }
        @endphp

        @foreach ($akun1 as $lev1)
            @php $total_akun1 = 0; @endphp

            <tr style="background:#4a4a4a; color:#fff;">
                <td colspan="3" align="center" style="font-weight:bold;">
                    {{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}
                </td>
            </tr>

            @foreach ($lev1->akun2 as $lev2)
                <tr style="background:#a7a7a7; font-weight:bold;">
                    <td>{{ $lev2->kode_akun }}.</td>
                    <td colspan="2">{{ $lev2->nama_akun }}</td>
                </tr>

                @foreach ($lev2->akun3 as $lev3)
                    @php
                        $saldo_akun3 = 0;
                        foreach ($lev3->rek as $rekening) {
                            $total_debit = $rekening->transaksiDebit->sum('jumlah');
                            $total_kredit = $rekening->transaksiKredit->sum('jumlah');
                            $saldo_rekening =
                                $rekening->jenis_mutasi === 'debet'
                                    ? $total_debit - $total_kredit
                                    : $total_kredit - $total_debit;
                            $saldo_akun3 += $saldo_rekening;
                        }
                        $total_akun1 += $saldo_akun3;
                        $bg = $i % 2 === 0 ? '#e6e6e6' : '#ffffff';
                        $i++;
                    @endphp

                    <tr style="background:{{ $bg }};">
                        <td>{{ $lev3->kode_akun }}.</td>
                        <td>{{ $lev3->nama_akun }}</td>
                        <td align="right">{{ formatSaldo($saldo_akun3) }}</td>
                    </tr>
                @endforeach
            @endforeach

            <tr style="background:#a7a7a7; font-weight:bold;">
                <td colspan="2" align="left">Jumlah {{ $lev1->nama_akun }}</td>
                <td align="right">{{ formatSaldo($total_akun1) }}</td>
            </tr>
            <tr>
                <td colspan="3" height="2"></td>
            </tr>
        @endforeach
    </table>
@endsection
