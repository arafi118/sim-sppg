<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Bahan</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($po->poDetail as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->bahanPangan->nama }}</td>
                <td>{{ number_format($detail->harga_satuan) }}</td>
                <td>{{ number_format($detail->jumlah) }}</td>
                <td>{{ number_format($detail->total_harga) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
