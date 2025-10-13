<div class="card mb-6">
    <h5 class="card-header">Daftar Rencana Anggaran Biaya</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Bahan Pangan</th>
                    <th class="text-end">Kebutuhan</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Total Harga</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
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
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $nama_bahan }}</td>
                        <td class="text-end">{{ number_format($jumlah, 2) }}</td>
                        <td class="text-center">{{ strtoupper($satuan) }}</td>
                        <td class="text-end">{{ number_format($harga) }}</td>
                        <td class="text-end">{{ number_format($total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end p-3">
        @if ($periode->approved == '0')
            <button class="btn btn-danger" disabled>Reject</button>
        @elseif ($periode->approved == '1')
            <button class="btn btn-primary" disabled>Approve</button>
        @else
            <button class="btn btn-danger me-3" id="btnReject">Reject</button>
            <button class="btn btn-primary" id="btnApprove">Approve</button>
        @endif
    </div>
</div>
