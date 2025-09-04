<style>
    /* Center teks placeholder Select2 */
    .select2-selection__placeholder {
        text-align: center !important;
    }
</style>
<form id="formPO">
    @csrf

    <!-- Baris Pemesanan PO + Filter Mitra -->
    <div class="mb-2 d-flex align-items-center justify-content-between">
        <!-- Teks Pemesanan PO di kiri -->
        <div>
            <strong style="font-size:20px;">Pemesanan (PO)</strong>
        </div>

        <!-- Filter Mitra di kanan -->
        <div class="d-flex justify-content-end" style="gap:10px; min-width:340px;">
            <label for="filterMitra" class="form-label visually-hidden">Filter Mitra:</label>
            <select id="filterMitra" class="form-select form-select-sm w-100">
                <option value="">-- Semua Mitra --</option>
                @php
                    $mitraUnique = collect();
                    foreach ($dataBahanPangan as $b) {
                        foreach ($b['mitra'] as $m) {
                            $mitraUnique->push($m);
                        }
                    }
                    $mitraUnique = $mitraUnique->unique('id');
                @endphp
                @foreach ($mitraUnique as $m)
                    <option value="{{ $m->id }}">{{ $m->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabel Bahan Pangan -->
    <table class="table table-bordered align-middle" id="tableBahan">
        <thead class="table-light">
            <tr style="text-align: center;">
                {{-- <th style="width:3%;">No</th> --}}
                <th style="width:13%;">Bahan Pangan</th>
                <th style="width:7%;">Satuan</th>
                <th style="width:15%;">Harga Satuan</th>
                <th style="width:15%;">Jumlah Kebutuhan</th>
                <th style="width:15%;">Jumlah</th>
                <th style="width:15%;">Total Harga</th>
            </tr>
            <meta name="csrf-token" content="{{ csrf_token() }}">

        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($dataBahanPangan as $b)
                <tr data-mitra="{{ implode(',', collect($b['mitra'])->pluck('id')->toArray()) }}">
                    {{-- <td>{{ $no }}</td> --}}
                    <td>{{ $b['nama'] }}</td>
                    <td>{{ $b['satuan'] }}</td>
                    <td class="text-end">{{ number_format($b['harga'], 0, ',', '.') }}</td>
                    <td class="text-end">
                        {{ number_format($b['jumlah'], 2, ',', '.') }}
                        <input type="hidden" name="jumlah_kebutuhan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}">
                    </td>
                    <td>
                        <input type="number" name="jumlah_input[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}" class="form-control form-control-sm jumlah-input"
                            data-harga="{{ $b['harga'] }}" data-id="{{ $b['bahan_pangan_id'] }}" step="0.01">
                    </td>
                    <td>
                        <input type="text" id="total_harga_{{ $b['bahan_pangan_id'] }}"
                            class="form-control form-control-sm text-end"
                            value="{{ number_format($b['jumlah'] * $b['harga'], 2, ',', '.') }}" readonly>
                        <input type="hidden" name="harga_satuan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['harga'] }}">
                    </td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-2">
        <button type="button" id="btnSimpanPO" class="btn btn-success">
            <i class="icon-base bx bx-folder me-1"></i> Simpan PO
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // CSRF untuk AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Simpan PO
        $('#btnSimpanPO').on('click', function() {
            let formData = $('#formPO').serialize();

            $.ajax({
                url: '{{ url('/app/rab/simpanPO') }}',
                type: 'POST',
                data: formData,
                success: function(res) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'PO berhasil disimpan. Apakah Anda ingin melihat detail?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Iya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/app/rab/detailPO/' + res.po_id;
                        }
                    });
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan saat menyimpan PO.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                        .message;
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#filterMitra').select2({
            placeholder: "-- Semua Mitra --",
            allowClear: true,
            width: '200px' // lebar bisa disesuaikan
        });

        // Filter mitra tetap jalan
        $('#filterMitra').on('change', function() {
            const selectedMitra = $(this).val();
            $('#tableBahan tbody tr').each(function() {
                const mitraIds = $(this).data('mitra').toString().split(',');
                $(this).toggle(selectedMitra === '' || mitraIds.includes(selectedMitra));
            });
        });

        // Hitung total harga otomatis
        $('.jumlah-input').each(function() {
            const input = $(this);

            function hitung() {
                const harga = parseFloat(input.data('harga')) || 0;
                const jumlah = parseFloat(input.val()) || 0;
                const total = harga * jumlah;
                $('#total_harga_' + input.data('id')).val(total.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }
            input.on('input', hitung);
            hitung();
        });
    });
</script>
