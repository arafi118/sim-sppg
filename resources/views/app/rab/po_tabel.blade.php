<form id="formPO">
    @csrf
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Pangan</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total Harga (input)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($dataBahanPangan as $b)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $b['nama'] }}</td>
                    <td>{{ $b['satuan'] }}</td>
                    <td class="text-end">{{ number_format($b['harga'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($b['jumlah'], 2, ',', '.') }}</td>
                    <td>
                        <!-- Input total harga bisa diedit -->
                        <input type="number" name="total_harga[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['harga'] * $b['jumlah'] }}" class="form-control form-control-sm" step="0.01">

                        <!-- Hidden field untuk jumlah dan harga satuan -->
                        <input type="hidden" name="jumlah[{{ $b['bahan_pangan_id'] }}]" value="{{ $b['jumlah'] }}">
                        <input type="hidden" name="harga_satuan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['harga'] }}">
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="text-end mt-2">
        <button type="button" id="btnSimpanPO" class="btn btn-success">Simpan PO</button>
    </div>
</form>
