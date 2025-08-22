@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title mb-0">
                <p class="card-subtitle" id="card-subtitle">
                    Tambah Mitra Baru
                </p>
                <h5 class="mt-1 me-2" id="card-title">
                    {{ $title ?? 'Tambah Mitra' }}
                </h5>
            </div>
        </div>
        <div class="card-body">
            <form action="/app/mitra" method="post" id="formMitra">
                @csrf

                <div class="row">
                    <div class="col-lg-3 col-12 mb-6 bahan">
                        <label for="nama_bahan" class="form-label">Nama Bahan</label>
                        <select id="nama_bahan" name="nama_bahan" class="select2 form-select form-select-lg"
                            data-allow-clear="true">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach ($kelompokPangan as $kp)
                                @php
                                    if (count($kp->bahanPangan) == 0) {
                                        continue;
                                    }
                                @endphp
                                <optgroup label="{{ $kp->nama }}">
                                    @foreach ($kp->bahanPangan as $bp)
                                        <option value="{{ $bp->id }}">
                                            {{ $bp->nama }} ({{ $bp->satuan }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-12 mb-6">
                        <label for="harga_beli" class="form-label">Harga Beli Bahan</label>
                        <input type="text" class="form-control" id="harga_beli" name="harga_beli" value="0" />
                    </div>
                    <div class="col-lg-3 col-12 mb-6">
                        <label for="nama_mitra" class="form-label">Nama Mitra</label>
                        <input type="text" class="form-control" id="nama_mitra" name="nama_mitra" />
                    </div>
                    <div class="col-lg-3 col-12 mb-6">
                        <label for="telpon" class="form-label">Telpon</label>
                        <input type="number" class="form-control" id="telpon" name="telpon" value="628" />
                    </div>
                    <div class="col-12 mb-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                </div>

                <div class="mb-0 d-flex justify-content-between align-items-center">
                    <a href="/app/mitra" class="btn btn-outline-secondary">
                        <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                        <span class="align-middle">Kembali</span>
                    </a>

                    <button type="button" id="simpanMitra" class="btn btn-primary ms-2">
                        <i class="icon-base bx bx-cloud-upload me-1"></i>
                        <span class="align-middle">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#harga_beli").maskMoney({
            allowZero: true,
            allowNegative: false,
            precision: 0,
        });

        $(document).on('click', '#simpanMitra', function(e) {
            e.preventDefault();

            var form = $('#formMitra');
            $.ajax({
                url: form.attr('action'),
                method: 'post',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Mitra berhasil ditambahkan. Tambahkan Mitra Baru?',
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Tambahkan",
                            cancelButtonText: "Tidak"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('input[type="text"], input[type="number"], textarea').val('');
                                $('#nama_bahan').val('').trigger('change');
                                $('#harga').val('0');
                                $('#nama_mitra').focus();
                            } else {
                                window.location.href = '/app/mitra';
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error ||
                            'Terjadi kesalahan saat menyimpan.'
                    });
                }
            })
        })
    </script>
@endsection
