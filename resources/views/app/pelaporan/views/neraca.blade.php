@extends('app.pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px !important; margin: 0; line-height: 1.2;">
                    <b style="font-size: inherit;">{{ strtoupper($judul) }}</b>
                </div>
                <div style="font-size: 16px !important; margin: 0; line-height: 1.2;">
                    <b style="font-size: inherit;">{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="3"></td>
        </tr>
        <tr style="background: #000; color: #fff;">
            <td width="10%">Kode</td>
            <td width="70%">Nama Akun</td>
            <td align="right" width="20%">Saldo</td>
        </tr>
        <tr>
            <td colspan="3" height="3"></td>
        </tr>

        @php
            $i = 0;

            // Fungsi untuk format saldo dengan tanda kurung jika negatif
            function formatSaldo($nilai)
            {
                $formatted = number_format(abs($nilai), 2, ',', '.');
                return $nilai < 0 ? '(' . $formatted . ')' : $formatted;
            }
        @endphp

        @foreach ($akun1 as $lev1)
            @php $total_akun1 = 0; @endphp

            <tr style="background: rgb(74,74,74); color:#fff;">
                <td colspan="3" align="center"><b>{{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}</b></td>
            </tr>

            @foreach ($lev1->akun2 as $lev2)
                <tr style="background: rgb(167,167,167); font-weight:bold;">
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
                        $bg = $i % 2 == 0 ? 'rgb(230,230,230)' : 'rgba(255,255,255)';
                        $i++;
                    @endphp

                    <tr style="background: {{ $bg }};">
                        <td>{{ $lev3->kode_akun }}.</td>
                        <td>{{ $lev3->nama_akun }}</td>
                        <td align="right">{{ formatSaldo($saldo_akun3) }}</td>
                    </tr>
                @endforeach
            @endforeach

            <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                <td height="15" colspan="2" align="left">
                    <b>Jumlah {{ $lev1->nama_akun }}</b>
                </td>
                <td align="right">{{ formatSaldo($total_akun1) }}</td>
            </tr>
            <tr>
                <td colspan="3" height="1"></td>
            </tr>
        @endforeach
    </table>
@endsection
