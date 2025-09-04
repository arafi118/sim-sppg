@extends('app.layouts.app')

@section('content')
    <title>{{ $title }}</title>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail PO</h4>

            @foreach ($referensiPOs as $refPo)
                @if ($refPo->poDetail->count() > 0)
                    <p>
                        Tanggal {{ \Carbon\Carbon::parse($refPo->tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                    </p>

                    <table class="table table-bordered mt-1 mb-3">
                        <thead>
                            <tr>
                                <th>Bahan Pangan</th>
                                <th>Mitra</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Kebutuhan</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalHarga = 0;
                            @endphp
                            @foreach ($refPo->poDetail as $detail)
                                <tr>
                                    <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                                    <td>{{ $detail->mitra->nama ?? '-' }}</td>
                                    <td align="center">{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td align="center">{{ number_format($detail->jumlah, 2, ',', '.') }} (Kg)</td>
                                    <td align="center">{{ number_format($detail->jumlah_input, 2, ',', '.') }} (Kg)</td>
                                    <td class="text-end">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
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
                                                <li>
                                                    <a class="dropdown-item btn-bayar text-success"
                                                        href="javascript:void(0)" data-bs-toggle="modal"
                                                        data-bs-target="#modalBayar" data-id="{{ $detail->id }}"
                                                        data-bahan="{{ $detail->bahanPangan->nama ?? '-' }}"
                                                        data-mitra="{{ $detail->mitra->nama ?? '-' }}"
                                                        data-satuan="{{ $detail->bahanPangan->satuan ?? '-' }}"
                                                        data-harga="{{ $detail->harga_satuan }}"
                                                        data-kebutuhan="{{ $detail->jumlah }}"
                                                        data-total="{{ $detail->total_harga }}"
                                                        data-sisa="{{ $detail->total_harga - $detail->jumlah_input }}">
                                                        <i class="bx bx-credit-card me-1"></i> Dibayar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php $totalHarga += $detail->total_harga; @endphp
                            @endforeach

                            <tr class="fw-bold">
                                <td colspan="6" class="text-center">Total</td>
                                <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            @endforeach

            <div class="mt-3 d-flex justify-content-end">
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

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Detail PO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2">
                            <!-- Bahan -->
                            <div class="col-md-6">
                                <label for="edit_bahan" class="form-label">Bahan</label>
                                <select class="form-select" id="edit_bahan" name="bahan_pangan_id" required>
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach ($bahanPangan as $bp)
                                        <option value="{{ $bp->id }}" data-satuan="{{ $bp->satuan }}"
                                            data-harga="{{ $bp->harga_jual }}" data-kebutuhan="{{ $bp->jumlah }}">
                                            {{ $bp->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mitra -->
                            <div class="col-md-6">
                                <label for="edit_mitra" class="form-label">Mitra</label>
                                <select class="form-select" id="edit_mitra" name="mitra_id" required>
                                    <option value="">-- Pilih Mitra --</option>
                                    @foreach ($po->poDetail as $detail)
                                        <option value="{{ $detail->mitra->id ?? '' }}"
                                            data-bahan="{{ $detail->bahanPangan->id ?? '' }}">
                                            {{ $detail->mitra->nama ?? '-' }}
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
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Bayar -->
    <div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('rab.bayar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="po_detail_id" id="bayar_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Pembayaran PO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2">
                            <!-- Baris 1: Bahan & Mitra -->
                            <div class="col-md-6">
                                <label class="form-label">Bahan</label>
                                <input type="text" class="form-control" id="bayar_bahan" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mitra</label>
                                <input type="text" class="form-control" id="bayar_mitra" readonly>
                            </div>

                            <!-- Baris 2: Satuan, Harga, Kebutuhan -->
                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="bayar_satuan" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga</label>
                                <input type="text" class="form-control" id="bayar_harga" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kebutuhan</label>
                                <input type="text" class="form-control" id="bayar_kebutuhan" readonly>
                            </div>

                            <!-- Baris 3: Sisa Tagihan & Jumlah Bayar -->
                            <div class="col-md-6">
                                <label class="form-label">Sisa Tagihan</label>
                                <input type="text" class="form-control" id="bayar_sisa" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Bayar</label>
                                <input type="number" step="0.01" class="form-control" name="jumlah_bayar"
                                    id="bayar_jumlah_bayar" required>
                            </div>
                            <!-- Baris 4: Total Tagihan -->
                            <div class="col-md-6">
                                <label class="form-label">Total Tagihan</label>
                                <input type="text" class="form-control" id="bayar_total" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bahanSelect = document.getElementById('edit_bahan');
            const mitraSelect = document.getElementById('edit_mitra');
            const jumlahInput = document.getElementById('edit_jumlah');
            const hargaInput = document.getElementById('edit_harga');
            const totalInput = document.getElementById('edit_total');
            const kebutuhanInput = document.getElementById('edit_kebutuhan');
            const satuanInput = document.getElementById('edit_satuan');

            const poDetails = {};
            document.querySelectorAll('.btn-edit').forEach(btn => {
                poDetails[btn.dataset.bahan_id] = {
                    jumlah: btn.dataset.jumlah,
                    kebutuhan: btn.dataset.kebutuhan,
                    harga: btn.dataset.harga,
                    mitra: btn.dataset.mitra_id
                };
            });

            function hitungTotalEdit() {
                const harga = parseFloat(hargaInput.value) || 0;
                const jumlah = parseFloat(jumlahInput.value) || 0;
                totalInput.value = (harga * jumlah).toLocaleString('id-ID');
            }

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bahanId = this.dataset.bahan_id;
                    const data = poDetails[bahanId] || {};

                    document.getElementById('edit_id').value = this.dataset.id;
                    bahanSelect.value = bahanId;
                    hargaInput.value = data.harga || 0;
                    jumlahInput.value = data.jumlah || 0;
                    kebutuhanInput.value = data.kebutuhan || 0;

                    Array.from(mitraSelect.options).forEach(opt => {
                        opt.style.display = (opt.dataset.bahan == bahanId || opt.value ==
                            '') ? 'block' : 'none';
                    });
                    mitraSelect.value = data.mitra || '';

                    const selectedOption = bahanSelect.options[bahanSelect.selectedIndex];
                    satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
                    hitungTotalEdit();
                });
            });

            bahanSelect.addEventListener('change', function() {
                const bahanId = this.value;
                const selectedOption = bahanSelect.options[bahanSelect.selectedIndex];
                satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
                hargaInput.value = selectedOption.getAttribute('data-harga') || 0;
                kebutuhanInput.value = selectedOption.getAttribute('data-kebutuhan') || 0;
                jumlahInput.value = 0;

                Array.from(mitraSelect.options).forEach(opt => {
                    opt.style.display = (opt.dataset.bahan == bahanId || opt.value == '') ?
                        'block' : 'none';
                });
                mitraSelect.value = '';
                hitungTotalEdit();
            });

            jumlahInput.addEventListener('input', hitungTotalEdit);

            const formEdit = document.querySelector('#largeModal form');
            formEdit.addEventListener('submit', function(e) {
                e.preventDefault(); // cegah submit default

                const formData = new FormData(formEdit);

                $.ajax({
                    url: formEdit.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'largeModal'));
                        modal.hide();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Detail PO berhasil diperbarui.',
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // reload halaman agar tabel update
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
            // Modal Bayar
            const jumlahBayarInput = document.getElementById('bayar_jumlah_bayar');
            const sisaInput = document.getElementById('bayar_sisa');
            const totalBayarInput = document.getElementById('bayar_total');

            document.querySelectorAll('.btn-bayar').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('bayar_id').value = this.dataset.id;
                    document.getElementById('bayar_bahan').value = this.dataset.bahan;
                    document.getElementById('bayar_mitra').value = this.dataset.mitra ?? '-';
                    document.getElementById('bayar_satuan').value = this.dataset.satuan;
                    document.getElementById('bayar_harga').value = parseFloat(this.dataset.harga)
                        .toLocaleString('id-ID');
                    document.getElementById('bayar_kebutuhan').value = this.dataset.kebutuhan;
                    totalBayarInput.value = parseFloat(this.dataset.total).toLocaleString('id-ID');
                    const sisaAwal = parseFloat(this.dataset.sisa) || 0;
                    sisaInput.value = sisaAwal.toLocaleString('id-ID');
                    jumlahBayarInput.value = '';
                });
            });


            if (jumlahBayarInput) {
                jumlahBayarInput.addEventListener('input', function() {
                    const total = parseFloat(totalBayarInput.value.replace(/\./g, '').replace(/,/g, '')) ||
                        0;
                    const bayar = parseFloat(this.value) || 0;
                    let sisa = total - bayar;
                    if (sisa < 0) sisa = 0;
                    sisaInput.value = sisa.toLocaleString('id-ID');
                });
            }
        });
    </script>
@endsection
