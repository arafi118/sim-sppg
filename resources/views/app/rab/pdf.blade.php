<style>
    * {
        font-family: 'Arial', sans-serif;
        font-size: 12px;
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
        padding: 4px;
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
            <th>No</th>
            <th>Bahan Pangan</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Kebutuhan</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    @php
        $grandTotal = 0;
    @endphp

    @foreach ($dataBahanPangan as $index => $b)
        @php
            $nama_bahan = $b['nama'];
            $satuan = $b['satuan'];
            $harga = $b['harga'];
            $jumlah = $b['jumlah'];

            $total = $harga * $jumlah;
            $grandTotal += $total;
        @endphp

        <tr>
            <td class="center">{{ $loop->iteration }}</td>
            <td>{{ $nama_bahan }}</td>
            <td class="center">{{ $satuan }}</td>
            <td class="right">{{ number_format($harga, 0, ',', '.') }}</td>
            <td class="right">{{ number_format($jumlah, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    @endforeach

    <tfoot>
        <tr>
            <th colspan="5" class="center">TOTAL</th>
            <th class="right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
