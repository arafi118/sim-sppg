@extends('app.layouts.app')

@section('content')
    <style>
        /* Hilangkan panah bawaan <select> */
        #edit_bahan {
            -webkit-appearance: none;
            /* untuk Safari/Chrome */
            -moz-appearance: none;
            /* untuk Firefox */
            appearance: none;
            /* standar */
            background-image: none !important;
            /* hilangkan ikon bawaan bootstrap */
        }

        /* Paksa tabel tanpa jarak antar cell */
        .table {
            border-collapse: collapse !important;
        }

        /* Kolom checkbox */
        .table th:first-child,
        .table td:first-child {
            width: 50px !important;
            text-align: center !important;
            vertical-align: middle !important;
            padding: 0 !important;
        }

        /* Checkbox pas di tengah */
        /* Samakan ukuran checkbox di header dan body */
        table .form-check-input {
            transform: scale(0.85);
            /* perkecil semuanya 85% */
            top: 0.1rem;
            /* sesuaikan posisi biar rata */
        }

        /* Samakan tinggi baris header dan body */
        table thead th,
        table tbody td {
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
            vertical-align: middle;
        }


        /* Kolom Bahan makin dekat */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            padding-left: 1px !important;
            transform: translateX(-5px);
        }
    </style>

    <title>{{ $title }}</title>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail PO</h4>

            @foreach ($referensiPOs as $refPo)
                @if ($refPo->poDetail->count() > 0)
                    <p>
                        Tanggal {{ \Carbon\Carbon::parse($refPo->tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                    </p>

                    <div class="">
                        <table class="table table-bordered mt-1 mb-3">
                            <thead>
                                <tr>
                                    {{-- <th style="width:30px; text-align:center; vertical-align:middle;">
                                        <input type="checkbox" class="form-check-input checkAll">
                                    </th> --}}

                                    <th style="width: 30px; text-align:center; vertical-align:middle;">No</th>
                                    {{-- No pindah setelah checkbox --}}
                                    <th style="width: 150px;">Bahan</th>
                                    <th style="width: 190px;">Mitra</th>
                                    <th style="width: 70px;">Harga</th>
                                    <th style="width: 100px;">Kebutuhan</th>
                                    <th style="width: 120px;">Jumlah</th>
                                    <th style="width: 100px;">Total</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalHarga = 0;
                                @endphp
                                @foreach ($refPo->poDetail as $detail)
                                    @php
                                        $sisa = $detail->total_harga - $detail->jumlah_bayar;
                                        $totalHarga += $detail->total_harga;

                                        if ($detail->jumlah_bayar == 0) {
                                            $status = '<span class="badge bg-danger">UNPAID</span>';
                                        } elseif ($detail->jumlah_bayar < $detail->total_harga) {
                                            $status = '<span class="badge bg-warning text-dark">PARTIAL</span>';
                                        } else {
                                            $status = '<span class="badge bg-success">PAID</span>';
                                        }
                                    @endphp
                                    <tr>
                                        {{-- <td style="text-align:center; vertical-align:middle;">
                                            <input type="checkbox" class="form-check-input checkbox-detail"
                                                value="{{ $detail->id }}" data-id="{{ $detail->id }}"
                                                data-bahan="{{ $detail->bahanPangan->nama ?? '-' }}"
                                                data-mitra="{{ $detail->mitra->nama ?? '-' }}"
                                                data-satuan="{{ $detail->bahanPangan->satuan ?? '-' }}"
                                                data-harga="{{ $detail->harga_satuan }}"
                                                data-kebutuhan="{{ $detail->jumlah }}"
                                                data-total="{{ $detail->total_harga }}"
                                                data-sisa="{{ $detail->total_harga - $detail->jumlah_bayar }}" checked>
                                        </td> --}}
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                                        <td>{{ $detail->mitra->nama ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td align="center">{{ $detail->jumlah }}
                                            ({{ $detail->bahanPangan->satuan ?? '-' }})
                                        </td>
                                        <td align="center">{{ $detail->jumlah_input }}</td>
                                        <td class="text-center">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                        <td>{!! $status !!}</td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-warning btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="icon-base bx bx-error-circle text-dark"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item btn-edit" href="javascript:void(0)"
                                                            data-bs-toggle="modal" data-bs-target="#largeModal"
                                                            data-id="{{ $detail->id }}"
                                                            data-bahan_id="{{ $detail->bahan_pangan_id }}"
                                                            data-mitra_id="{{ $detail->mitra_id }}"
                                                            data-harga="{{ $detail->harga_satuan }}"
                                                            data-jumlah="{{ $detail->jumlah_input }}"
                                                            data-kebutuhan="{{ $detail->jumlah }}">
                                                            <i class="bx bx-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/app/rab/po/cetak_detail/' . $detail->id) }}"
                                                            class="dropdown-item text-info" target="_blank">
                                                            <i class="bx bx-check-circle me-1"></i> Diterima
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td colspan="6" class="text-center">Total</td>
                                    <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
            <div class="mt-3 d-flex justify-content-end">
                <!-- Tombol Bayar PO -->
                <button class="btn btn-primary d-none me-2" id="btnBayarPO" data-bs-toggle="modal"
                    data-bs-target="#modalBayarPO">
                    Bayar PO
                </button>

                <!-- Tombol Kembali -->
                <a href="{{ url('/app/rab') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rab.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    {{-- hidden untuk bahan biar tetap terkirim walau select disable --}}
                    <input type="hidden" name="bahan_pangan_id" id="hidden_bahan">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Detail PO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2">
                            <!-- Bahan -->
                            <div class="col-md-6">
                                <label for="edit_bahan" class="form-label">Bahan</label>
                                <select class="form-select" id="edit_bahan" name="bahan_pangan_id">
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach ($bahanPangan as $bp)
                                        <option value="{{ $bp->id }}" data-satuan="{{ $bp->satuan }}"
                                            data-harga="{{ $bp->harga_jual }}">
                                            {{ $bp->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Mitra -->
                            <div class="col-md-6">
                                <label for="edit_mitra" class="form-label">Mitra</label>
                                <select class="form-select select2" id="edit_mitra" name="mitra_id" required>
                                    <option value="">-- Pilih Mitra --</option>
                                    @foreach ($mitras as $m)
                                        <option value="{{ $m->id }}" data-bahan="{{ $m->bahan_pangan_id }}">
                                            {{ $m->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="edit_satuan" readonly>
                            </div>
                            <!-- Harga -->
                            <div class="col-md-6">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" id="edit_harga" name="harga_satuan" readonly>
                            </div>
                            <!-- Kebutuhan -->
                            <div class="col-md-6">
                                <label class="form-label">Kebutuhan</label>
                                <input type="number" class="form-control" id="edit_kebutuhan" readonly>
                            </div>
                            <!-- Jumlah -->
                            <div class="col-md-6">
                                <label class="form-label">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" id="edit_jumlah"
                                    name="jumlah_input" required>
                            </div>
                            <!-- Total -->
                            <div class="col-md-6">
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control" id="edit_total" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Bayar PO -->
    <div class="modal fade" id="modalBayarPO" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 80%; width: 80%;">
            <div class="modal-content">
                <form id="formBayar" action="{{ url('app/rab/bayar_po') }}" method="POST">
                    @csrf
                    <input type="hidden" name="po_id" value="{{ $po->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Pembayaran Seluruh PO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:9%;">Bahan</th>
                                        <th class="text-center" style="width:14%;">Mitra</th>
                                        <th class="text-end" style="width:10%;">Harga</th>
                                        <th class="text-end" style="width:14%;">Kebutuhan</th>
                                        <th class="text-end" style="width:15%;">Sisa Tagihan</th>
                                        <th class="text-end" style="width:15%;">Jumlah Bayar</th>
                                        <th class="text-end" style="width:15%;">Total Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody id="listBayarPO">
                                    <!-- akan diisi otomatis dari JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Bayar PO (AJAX) ---
            document.getElementById('btnBayarPO').addEventListener('click', function() {
                const listBayar = document.getElementById('listBayarPO');
                listBayar.innerHTML = '';

                document.querySelectorAll('.checkbox-detail:checked').forEach(chk => {
                    const harga = parseInt(chk.dataset.harga) || 0;
                    const kebutuhan = parseFloat(chk.dataset.kebutuhan) || 0;
                    const sisa = parseFloat(chk.dataset.sisa) || 0;
                    const total = parseFloat(chk.dataset.total) || 0;

                    const hargaFormatted = harga.toLocaleString('id-ID');
                    const kebutuhanFormatted = kebutuhan.toString().replace('.', ',');
                    const sisaFormatted = sisa.toLocaleString('id-ID');
                    const totalFormatted = total.toLocaleString('id-ID');

                    listBayar.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td style="text-align: left !important; padding-left: 0;">${chk.dataset.bahan}</td>
                        <td class="text-start" style="padding-left: 0;">${chk.dataset.mitra}</td>
                        <td class="text-end">${hargaFormatted}</td>
                        <td class="text-end">${kebutuhanFormatted} (${chk.dataset.satuan})</td>
                        <td class="text-end">
                            <span class="sisaTagihan">${sisaFormatted}</span>
                            <input type="hidden" class="sisaAwal" value="${sisa}">
                        </td>
                        <td>
                            <input type="hidden" name="detail_id[]" value="${chk.dataset.id}">
                            <input type="text" name="jumlah_bayar[]" class="form-control form-control-sm text-end jumlahBayar"
                                placeholder="0"
                                oninput="
                                this.value = this.value.replace(/[^0-9.]/g,'');
                                if(parseFloat(this.value) > ${sisa}) this.value = '${sisa}';
                                ">
                        </td>
                        <td class="text-end totalTagihan">${totalFormatted}</td>
                    </tr>
                `);
                });

                if (listBayar.innerHTML.trim() === '') {
                    listBayar.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada detail dipilih
                        </td>
                    </tr>
                `;
                }
            });

            // Update sisa tagihan saat input jumlah bayar
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('jumlahBayar')) {
                    const tr = e.target.closest('tr');
                    const sisa = parseFloat(tr.querySelector('.sisaAwal').value) || 0;
                    let bayar = parseFloat(e.target.value) || 0;
                    if (bayar > sisa) bayar = sisa;
                    tr.querySelector('.sisaTagihan').textContent = (sisa - bayar).toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    });
                }
            });

            // Submit Bayar PO via AJAX
            const formBayar = document.getElementById('formBayar');
            formBayar.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'modalBayarPO'));
                        modal.hide();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran PO berhasil disimpan.',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
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

            // Check All ⇆ Checkbox Detail
            document.querySelectorAll('.checkAll').forEach(checkAll => {
                const table = checkAll.closest('table');
                const checkboxes = table.querySelectorAll('.checkbox-detail');

                checkAll.checked = [...checkboxes].every(chk => chk.checked);

                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(chk => chk.checked = this.checked);
                });

                checkboxes.forEach(chk => {
                    chk.addEventListener('change', function() {
                        checkAll.checked = [...checkboxes].every(c => c.checked);
                    });
                });
            });
        });
    </script>
    <script>
        $(function() {
            const $mitra = $('#edit_mitra');
            const $bahan = $('#edit_bahan');
            const $jumlah = $('#edit_jumlah');
            const $harga = $('#edit_harga');
            const $total = $('#edit_total');
            const $satuan = $('#edit_satuan');
            const $kebutuhan = $('#edit_kebutuhan');

            // lock dropdown bahan (user tidak bisa pilih manual)
            $bahan.on('mousedown', function(e) {
                e.preventDefault();
            });

            // hitung total harga
            function hitungTotal() {
                const h = parseFloat(String($harga.val()).replace(/[^0-9\.\-]/g, '')) || 0;
                const j = parseFloat($jumlah.val()) || 0;
                $total.val((h * j).toLocaleString('id-ID'));
            }

            // buat map bahan
            const bahanMap = {};
            $bahan.find('option').each(function() {
                const $opt = $(this);
                if (!$opt.val()) return;
                bahanMap[$opt.val()] = {
                    satuan: $opt.data('satuan') || '',
                    harga: parseFloat($opt.data('harga')) || 0
                };
            });

            // simpan data detail dari tombol edit
            const poDetails = {};
            $('.btn-edit').each(function() {
                const $btn = $(this);
                const id = $btn.data('id');
                if (!id) return;
                poDetails[id] = {
                    id: id,
                    bahan: $btn.data('bahan_id') || $btn.data('bahan'),
                    mitra: $btn.data('mitra_id') || $btn.data('mitra'),
                    harga: $btn.data('harga') || 0,
                    jumlah: $btn.data('jumlah') || 0,
                    kebutuhan: $btn.data('kebutuhan') || 0
                };
            });

            // klik tombol Edit → isi modal
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const d = poDetails[id] || {};

                $mitra.data('activeDetail', id);
                $('#edit_id').val(d.id || '');
                $mitra.val(d.mitra || '').trigger('change');
                $bahan.val(d.bahan || '');
                const bm = bahanMap[$bahan.val()] || {};
                $satuan.val(bm.satuan || '');
                $harga.val(d.harga || bm.harga || 0);
                $kebutuhan.val(d.kebutuhan || 0);
                $jumlah.val(d.jumlah || 0);
                hitungTotal();
                if ($bahan.hasClass('select2-hidden-accessible')) $bahan.trigger('change.select2');
            });

            // saat mitra diganti
            $mitra.on('change', function() {
                const bahanId = $(this).find(':selected').data('bahan') || '';
                const activeId = $(this).data('activeDetail');
                const d = poDetails[activeId] || {};

                if (!bahanId) {
                    $bahan.val('');
                    $satuan.val('');
                    $harga.val(0);
                    $kebutuhan.val(0);
                    $jumlah.val(0);
                    hitungTotal();
                    if ($bahan.hasClass('select2-hidden-accessible')) $bahan.trigger('change.select2');
                    return;
                }

                $bahan.val(String(bahanId));
                if ($bahan.hasClass('select2-hidden-accessible')) $bahan.trigger('change.select2');
                const bm = bahanMap[String(bahanId)] || {};
                $satuan.val(bm.satuan || '');
                $harga.val(bm.harga || 0);
                $kebutuhan.val(d.kebutuhan || 0);
                $jumlah.val(d.jumlah || 0);
                hitungTotal();
            });

            // jumlah / harga berubah → total ikut
            $jumlah.on('input', hitungTotal);
            $harga.on('input', hitungTotal);

            // AJAX submit form
            $('#largeModal form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);

                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: $form.serialize(),
                    success: function(res) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'largeModal'));
                        modal.hide();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Detail PO berhasil diperbarui.',
                            icon: 'success'
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan saat menyimpan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        Swal.fire('Error!', msg, 'error');
                    }
                });
            });
        });
    </script>
@endsection
