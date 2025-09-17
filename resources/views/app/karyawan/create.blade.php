@extends('app.layouts.app')
@section('content')
    <form action="/app/karyawan" method="post" id="FromKaryawan" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="level_id" id="level_id" value="6">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Tambah Karyawan Baru</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-6">
                            <label for="nik" class="form-label">Nik</label>
                            <input type="text" maxlength="16" class="form-control" id="nik" name="nik"
                                placeholder="masukkan nik karyawan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="masukkan nama karyawan">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="text" id="tanggal_lahir" name="tanggal_lahir" class="form-control dob-picker"
                            placeholder="YYYY-MM-DD" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="mb-6">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="jenis_kelamin" id="laki" value="L"
                                    checked>
                                <label class="btn btn-outline-secondary w-50" for="laki">Laki-laki</label>
                                <input type="radio" class="btn-check" name="jenis_kelamin" id="perempuan" value="P">
                                <label class="btn btn-outline-secondary w-50" for="perempuan">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-6">
                            <label for="telpon" class="form-label">No Telepon</label>
                            <input type="text" class="form-control" id="telpon" name="telpon"
                                placeholder="masukkan no telepon">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="text" id="tanggal_masuk" name="tanggal_masuk" class="form-control dob-picker"
                            placeholder="YYYY-MM-DD" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="mb-6">
                            <label for="gaji" class="form-label">Satuan Gaji</label>
                            <input type="text" class="form-control" id="gaji" name="gaji"
                                placeholder="masukkan satuan gaji">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="foto">Foto</label>
                        <input type="file" id="foto" name="foto" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-6">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="1" placeholder="Alamat Lengkap"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="masukkan username">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary d-flex align-items-center" type="button"
                                    id="togglePassword">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                                        <line x1="1" y1="1" x2="23" y2="23"
                                            stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-0 d-flex justify-content-between align-items-center">
                    <a href="/app/karyawan" class="btn btn-outline-secondary"><i
                            class="icon-base bx bx-left-arrow-alt me-1"></i>Kembali</a>
                    <button type="button" id="simpanKaryawan" class="btn btn-primary ms-2"><i
                            class="icon-base bx bx-cloud-upload me-1"></i>Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })
        $('#foto').on('change', function() {
            let f = this.files[0];
            if (f) {
                let a = ['jpg', 'jpeg', 'png'],
                    e = f.name.split('.').pop().toLowerCase();
                if (!a.includes(e)) {
                    $(this).val('');
                    Toast.fire({
                        icon: 'error',
                        title: 'Hanya diperbolehkan file JPG, JPEG, atau PNG!'
                    })
                } else {
                    Toast.fire({
                        icon: 'success',
                        title: 'File valid: ' + e.toUpperCase()
                    })
                }
            }
        })
        $('#togglePassword').on('click', () => {
            let p = $('#password')[0],
                i = $('#eyeIcon')[0];
            p.type = p.type === 'password' ? 'text' : 'password';
            i.innerHTML = p.type === 'password' ?
                `<path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>` :
                `<path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>`
        })
        $(".dob-picker").flatpickr({
            monthSelectorType: "static"
        })
        $("#gaji").on("input", function() {
            let v = $(this).val().replace(/\D/g, "");
            $(this).val(new Intl.NumberFormat("id-ID").format(v))
        })
        $(document).on('click', '#simpanKaryawan', function(e) {
            e.preventDefault();
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');
            let f = $('#FromKaryawan')[0],
                fd = new FormData(f),
                url = $('#FromKaryawan').attr('action');
            $.ajax({
                type: 'POST',
                url: url,
                data: fd,
                processData: false,
                contentType: false,
                success: function(r) {
                    if (r.success) {
                        Swal.fire({
                            title: r.msg,
                            text: "Tambahkan Register Karyawan Baru?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Tambahkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload()
                            } else {
                                window.location.href = '/app/karyawan'
                            }
                        })
                    }
                },
                error: function(r) {
                    const s = r.responseJSON;
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                    if (s && typeof s === 'object') {
                        $.each(s, function(k, m) {
                            $('#' + k).addClass('is-invalid');
                            $('#msg_' + k).html(m[0])
                        })
                    }
                }
            })
        })
    </script>
@endsection
