<style>
    /* Center teks placeholder Select2 */
    .select2-selection__placeholder {
        text-align: center !important;
    }

    /* Tinggi select2 dan input sama */
    .select2-container .select2-selection--single {
        height: 38px;
        /* sesuaikan tinggi select2 */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        /* agar teks di tengah */
    }

    .jumlah-input,
    #tableBahan td input[type="text"] {
        height: 38px;
        /* samakan dengan select2 */
    }
</style>

<form id="formPO">
    @csrf

    <!-- Tabel Bahan Pangan -->
    <table class="table table-bordered align-middle" id="tableBahan">
        <thead class="table-light">
            <tr style="text-align: center;">
                <th style="width:15%;">Bahan Pangan</th>
                <th style="width:7%;">Satuan</th>
                <th style="width:10%;">Harga</th>
                <th style="width:10%;">Kebutuhan</th>
                <th style="width:15%;">Mitra</th>
                <th style="width:15%;">Jumlah</th>
                <th style="width:15%;">Total Harga</th>
            </tr>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </thead>
        <tbody>
            @foreach ($dataBahanPangan as $b)
                <tr data-mitra="{{ implode(',', collect($b['mitra'])->pluck('id')->toArray()) }}">
                    <td>{{ $b['nama'] }}</td>
                    <td>{{ $b['satuan'] }}</td>
                    <td class="text-end">{{ number_format($b['harga'], 0) }}</td>
                    <td class="text-end">
                        {{ number_format($b['jumlah'], 2) }}
                        <input type="hidden" name="jumlah_kebutuhan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm mitra-select" style="width:100%">
                            <option value="">-- Pilih Mitra --</option>
                            @foreach ($b['mitra'] as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="mitra_id[{{ $b['bahan_pangan_id'] }}]" class="mitra-hidden">
                    </td>
                    <td>
                        <input type="number" name="jumlah_input[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}" class="form-control form-control-sm jumlah-input"
                            data-harga="{{ $b['harga'] }}" data-id="{{ $b['bahan_pangan_id'] }}" step="0.01">
                    </td>
                    <td>
                        <input type="text" id="total_harga_{{ $b['bahan_pangan_id'] }}"
                            class="form-control form-control-sm text-end"
                            value="{{ number_format($b['jumlah'] * $b['harga'], 2) }}" readonly>
                        <input type="hidden" name="harga_satuan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['harga'] }}">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" id="btnSimpanPO" class="btn btn-primary"> Simpan PO
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

        // Inisialisasi Select2 per baris & sinkron hidden input + filter baris
        $('.mitra-select').each(function() {
            $(this).select2({
                placeholder: "-- Pilih Mitra --",
                allowClear: true,
                width: '100%'
            });

            $(this).on('change', function() {
                const selectedMitra = $(this).val();
                $(this).siblings('.mitra-hidden').val(selectedMitra);

                // Filter tabel: tampilkan baris yang punya mitra yg dipilih
                $('#tableBahan tbody tr').each(function() {
                    const mitraIds = $(this).data('mitra').toString().split(',');
                    if (selectedMitra === '' || mitraIds.includes(selectedMitra)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
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

        // Simpan PO via AJAX
        $('#btnSimpanPO').on('click', function() {
            let formData = [];

            // Loop tiap baris tabel
            $('#tableBahan tbody tr').each(function() {
                const mitraId = $(this).find('.mitra-hidden').val();

                // Hanya ambil baris yang punya mitra dipilih
                if (mitraId) {
                    $(this).find('input[name], select[name]').each(function() {
                        formData.push({
                            name: $(this).attr('name'),
                            value: $(this).val()
                        });
                    });
                }
            });

            $.ajax({
                url: '{{ url('/app/rab/simpanPO') }}',
                type: 'POST',
                data: $.param(formData),
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
</script>
