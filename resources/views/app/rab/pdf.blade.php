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

    <!-- Spacer -->
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
        $menuNo = 1;
        $grandTotal = 0;
    @endphp

    @foreach ($rancangan as $r)
        @foreach ($r->rancanganMenu as $rm)
            @php
                $menuTotal = 0;
                $menuName = $rm->menu->nama ?? '-';
            @endphp

            <!-- Judul Menu -->
            <tr>
                <td class="center">{{ $menuNo }}</td>
                <td colspan="5" style="font-weight: bold;">{{ $menuName }}</td>
            </tr>

            <!-- List Bahan -->
            @foreach ($rm->menu->resep as $index => $resep)
                @php
                    $bp = $resep->bahanPangan;
                    $jumlah = $resep->gramasi ?? 0;
                    $harga = $bp->harga_jual ?? 0;
                    $total = $jumlah * $harga;

                    $menuTotal += $total;
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td class="center">{{ $menuNo . '.' . ($index + 1) }}</td>
                    <td>{{ $bp->nama ?? '-' }}</td>
                    <td class="center">{{ $bp->satuan ?? '-' }}</td>
                    <td class="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($jumlah, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Subtotal per Menu -->
            <tr>
                <th colspan="5" class="center">Subtotal {{ $menuName }}</th>
                <th class="right">{{ number_format($menuTotal, 0, ',', '.') }}</th>
            </tr>

            @php $menuNo++; @endphp
        @endforeach
    @endforeach

    <!-- Grand Total -->
    <tfoot>
        <tr>
            <th colspan="5" class="center">TOTAL</th>
            <th class="right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
