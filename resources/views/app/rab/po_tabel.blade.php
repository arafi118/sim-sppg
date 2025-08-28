<form id="formPO">
    @csrf
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th width="15%">Bahan Pangan</th>
                <th>Satuan</th>
                <th width="15%">Harga Satuan</th>
                <th>Jumlah</th>
                <th>Input Jumlah</th>
                <th>Total Harga</th>
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

                    <!-- jumlah kebutuhan dari rancangan -->
                    <td class="text-end">
                        {{ number_format($b['jumlah'], 2, ',', '.') }}
                        <input type="hidden" name="jumlah_kebutuhan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}"> Kg
                    </td>
                    <td>
                        <input type="number" name="jumlah_input[{{ $b['bahan_pangan_id'] }}]" value="0"
                            class="form-control form-control-sm jumlah-input" data-harga="{{ $b['harga'] }}"
                            data-id="{{ $b['bahan_pangan_id'] }}" step="0.01">
                    </td>

                    <!-- total harga otomatis -->
                    <td>
                        <input type="text" id="total_harga_{{ $b['bahan_pangan_id'] }}"
                            class="form-control form-control-sm text-end" value="0" readonly>

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
<script>
    document.querySelectorAll('.jumlah-input').forEach(input => {
        input.addEventListener('input', function() {
            let harga = parseFloat(this.dataset.harga) || 0;
            let jumlah = parseFloat(this.value) || 0;
            let total = harga * jumlah;

            document.getElementById('total_harga_' + this.dataset.id).value =
                total.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        });
    });
</script>
