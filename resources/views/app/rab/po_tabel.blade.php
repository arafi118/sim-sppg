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
                <th style="width:18%;">Bahan Pangan</th>
                <th style="width:10%;">Harga</th>
                <th style="width:9%;">Kebutuhan</th>
                <th style="width:15%;">Total</th>
            </tr>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </thead>
        <tbody>
            @foreach ($dataBahanPangan as $b)
                <tr data-mitra="{{ implode(',', collect($b['mitra'])->pluck('id')->toArray()) }}">
                    <td>{{ $b['nama'] }}</td>
                    <td class="text-end">{{ number_format($b['harga'], 2) }}</td>
                    <td class="text-end">
                        {{ $b['jumlah'] }}
                        <input type="hidden" name="jumlah_kebutuhan[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}"> ({{ $b['satuan'] }})
                        <input type="hidden" name="jumlah_input[{{ $b['bahan_pangan_id'] }}]"
                            value="{{ $b['jumlah'] }}" data-harga="{{ $b['harga'] }}"
                            data-id="{{ $b['bahan_pangan_id'] }}">
                    </td>

                    <td align="right">
                        {{ number_format($b['jumlah'] * $b['harga'], 2) }}
                        <input type="hidden" id="total_harga_{{ $b['bahan_pangan_id'] }}"
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

        // Inisialisasi Select2 + sinkronisasi ke hidden input
        $('.select2').select2({
            placeholder: "-- Pilih Mitra --",
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            const selectedMitra = $(this).val();
            $(this).closest('td').find('.mitra-hidden').val(selectedMitra);
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
