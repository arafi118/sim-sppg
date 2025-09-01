<style>
    * {
        font-family: 'Arial', sans-serif;
        font-size: 12px;
        line-height: 1.5;
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

    .tanggal-row {
        background: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }
</style>

<div
    style="flex: 1; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 13pt; margin-bottom: 20px;">
    PESANAN PEMBELIAN (PO)
</div>

<table class="tb-border">
    <thead>
        <tr>
            <th>No</th>
            <th>Bahan Pangan</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
            <th>Kebutuhan</th>
            <th>Jumlah Input</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $grandTotal = 0;
        @endphp

        @foreach ($pos as $po)
            <!-- Baris pembatas tanggal -->
            <tr class="tanggal-row">
                <td colspan="7">
                    Tanggal {{ \Carbon\Carbon::parse($po->tanggal)->translatedFormat('d F Y') }}
                </td>
            </tr>

            @php
                $subTotal = 0;
            @endphp

            @foreach ($po->poDetail as $detail)
                @php
                    $total = $detail->total_harga;
                    $subTotal += $total;
                @endphp
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                    <td class="center">{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                    <td class="right">{{ number_format($detail->harga_satuan) }}</td>
                    <td class="right">{{ number_format($detail->jumlah, 2) }} (Kg)</td>
                    <td class="right">{{ rtrim(rtrim($detail->jumlah_input, '0'), '.') }} (Kg)</td>
                    <td class="right">{{ number_format($total) }}</td>
                </tr>
            @endforeach

            <!-- subtotal per tanggal -->
            <tr>
                <th colspan="6" class="center">Sub Total</th>
                <th class="right">{{ number_format($subTotal) }}</th>
            </tr>

            @php
                $grandTotal += $subTotal;
            @endphp
        @endforeach

        <!-- total keseluruhan -->
        <tr>
            <th colspan="6" class="center" style="background:#f2f2f2;">TOTAL KESELURUHAN</th>
            <th class="right" style="background:#f2f2f2;">{{ number_format($grandTotal) }}</th>
        </tr>
    </tbody>

</table>
