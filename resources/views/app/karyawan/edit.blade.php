@extends('app.layouts.app')
@section('content')
    <form action="/app/karyawan/{{ $karyawan->id }}" method="POST" id="FormKaryawanEdit">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Edit Karyawan</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="nik" class="form-label">Nik</label>
                            <input type="text" maxlength="16" class="form-control" id="nik" name="nik"
                                value="{{ $karyawan->nik }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ $karyawan->nama }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="level_id" class="form-label">Level Hak Akses</label>
                            <select id="level_id" name="level_id" class="select2 form-select form-select-lg"
                                data-allow-clear="true">
                                <option value="">-- Pilih Level --</option>
                                @foreach ($levels as $lev)
                                    <option value="{{ $lev->id }}"
                                        {{ $karyawan->level_id == $lev->id ? 'selected' : '' }}>
                                        {{ $lev->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="text" id="tanggal_lahir" name="tanggal_lahir" class="form-control dob-picker"
                            value="{{ $karyawan->tanggal_lahir }}" />
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="jenis_kelamin" id="laki" value="L"
                                    {{ $karyawan->jenis_kelamin == 'L' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-50" for="laki">Laki-laki</label>
                                <input type="radio" class="btn-check" name="jenis_kelamin" id="perempuan" value="P"
                                    {{ $karyawan->jenis_kelamin == 'P' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-50" for="perempuan">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="telpon" class="form-label">No Telepon</label>
                            <input type="text" class="form-control" id="telpon" name="telpon"
                                value="{{ $karyawan->telpon }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="text" id="tanggal_masuk" name="tanggal_masuk" class="form-control dob-picker"
                            value="{{ $karyawan->tanggal_masuk }}" />
                    </div>
                    <div class="col-6">
                        <div class="mb-6">
                            <label for="gaji" class="form-label">Satuan Gaji</label>
                            <input type="text" class="form-control" id="gaji" name="gaji"
                                value="{{ number_format($karyawan->gaji, 0, ',', '.') }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-6">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="1">{{ $karyawan->alamat }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-6">
                        <div class="mb-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ $karyawan->username }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="Kosongkan jika tidak diganti">
                        </div>
                    </div>
                </div>

                <div class="mb-0 d-flex justify-content-between align-items-center mt-4">
                    <a href="/app/karyawan" class="btn btn-outline-secondary">
                        <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                        <span class="align-middle">Kembali</span>
                    </a>
                    <div d-flex>
                        <button type="submit" id="updateKaryawan" class="btn btn-primary ms-2">
                            <i class="icon-base bx bx-cloud-upload me-1"></i>
                            <span class="align-middle">Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(".dob-picker").flatpickr({
            monthSelectorType: "static"
        });

        $("#gaji").on("input", function() {
            let value = $(this).val().replace(/\D/g, "");
            $(this).val(new Intl.NumberFormat("id-ID").format(value));
        });

        $(document).on('click', '#updateKaryawan', function(e) {
            e.preventDefault();
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            var form = $('#FormKaryawanEdit');
            var actionUrl = form.attr('action');

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toast.fire({
                            icon: 'success',
                            title: result.msg
                        });

                        setTimeout(() => {
                            window.location.href = '/app/karyawan';
                        }, 1500);
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Toast.fire({
                        icon: 'error',
                        title: 'Cek kembali input yang anda masukkan'
                    });
                    if (response && typeof response === 'object') {
                        $.each(response, function(key, message) {
                            $('#' + key).addClass('is-invalid');
                            $('#msg_' + key).html(message[0]);
                        });
                    }
                }
            });
        });
    </script>
@endsection
