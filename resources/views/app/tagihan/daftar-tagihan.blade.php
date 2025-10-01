<table class="table table-striped">
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
            <td class="center">
                {{ $loop->iteration }}
                <input type="hidden" name="bahan_pangan[{{ $index }}]" id="{{ $index }}"
                    value="{{ json_encode($b) }}">
            </td>
            <td>{{ $nama_bahan }}</td>
            <td class="center">{{ $satuan }}</td>
            <td class="right">{{ number_format($harga) }}</td>
            <td class="right">{{ number_format($jumlah, 2) }}</td>
            <td class="right">{{ number_format($total) }}</td>
        </tr>
    @endforeach

    <tfoot>
        <tr>
            <th colspan="5" class="center">TOTAL</th>
            <th class="right">
                {{ number_format($grandTotal) }}
                <input type="hidden" name="grand_total" id="grand_total" value="{{ $grandTotal }}">
            </th>
        </tr>
    </tfoot>
</table>
