<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($po->status === 'DIBUAT')
            <span class="badge bg-secondary">Status: Dibuat</span>
        @elseif ($po->status === 'DIKIRIM')
            <span class="badge bg-info">Status: Dikirim</span>
        @elseif ($po->status === 'DITERIMA')
            <span class="badge bg-success">Status: Diterima</span>
        @elseif ($po->status === 'DIBATALKAN')
            <span class="badge bg-danger">Status: Dibatalkan</span>
        @endif
    </div>
    @if ($po->status === 'DIBATALKAN')
        <div class="alert alert-danger mb-0 p-2 py-1">
            <strong>Alasan Batal:</strong> {{ $po->alasan_batal }}
        </div>
    @endif
</div>

<div class="table-responsive">

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
            @php
                $total = 0;
            @endphp
            @foreach ($po->poDetail as $detail)
                @php
                    $total += $detail->total_harga;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->bahanPangan->nama }}</td>
                    <td class="text-end">{{ number_format($detail->harga_satuan) }}</td>
                    <td>{{ number_format($detail->jumlah, 2) }} ({{ $detail->bahanPangan->satuan }})</td>
                    <td class="text-end">{{ number_format($detail->total_harga) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th class="text-end">{{ number_format($total) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
