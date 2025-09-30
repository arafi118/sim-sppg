<style>
    * {
        font-family: 'Arial', sans-serif;
        font-size: 8px;
        line-height: 1.5;
    }

    .judul {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    .tb-border th,
    .tb-border td {
        border: 1px solid #000;
        padding: 1px 4px;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }
</style>
<title>{{ $title ?? 'RAB' }}</title>

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
    <!-- Judul -->
    <div style="flex: 1; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 13pt;">
        RENCANA ANGGARAN BIAYA (RAB)
    </div>

    <!-- Subjudul -->
    <div style="text-align: center; font-weight: normal; text-transform: uppercase; font-size: 10pt;">
        @if ($jenis === 'Harian')
            HARIAN TANGGAL {{ \Carbon\Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') }}
        @else
            PERIODE {{ \Carbon\Carbon::parse($tanggalParam[0])->translatedFormat('d F Y') }}
            S.D {{ \Carbon\Carbon::parse($tanggalParam[1])->translatedFormat('d F Y') }}
        @endif
    </div>

    <div style="width: 150px;"></div>
</div>
<table class="tb-border">
    <thead>
        <tr>
            <th width="2%">No</th>
            <th>Nama Bahan</th>
            @foreach ($daftarTanggal as $tanggalPeriode)
                <th width="3%">
                    {{ $tanggalPeriode['nama_hari'] }} {{ $tanggalPeriode['hari'] }}
                </th>
            @endforeach
            <th width="4%">Jumlah</th>
            <th width="4%">Satuan</th>
            <th width="8%">Harga</th>
            <th width="10%">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataBahanPangan as $bahanPangan)
            @php
                $jumlahKebutuhan = 0;
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ ucwords(strtolower($bahanPangan['nama'])) }}</td>
                @foreach ($daftarTanggal as $tanggalPeriode)
                    @php
                        $kebutuhan = 0;
                        if (isset($bahanPangan['jumlah'][$tanggalPeriode['tanggal']])) {
                            $kebutuhan = $bahanPangan['jumlah'][$tanggalPeriode['tanggal']];
                        }

                        $jumlahKebutuhan += $kebutuhan;
                    @endphp
                    <td align="center">
                        {{ $kebutuhan != 0 ? number_format($kebutuhan, 2) : '' }}
                    </td>
                @endforeach
                <td align="center">{{ $jumlahKebutuhan }}</td>
                <td align="center">{{ $bahanPangan['satuan'] }}</td>
                <td align="right">{{ number_format($bahanPangan['harga']) }}</td>
                <td align="right">{{ number_format($jumlahKebutuhan * $bahanPangan['harga']) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
