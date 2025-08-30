@extends('app.layouts.app')
@section('content')
    <form action="/app/karyawan" method="post" id="FromKaryawan">
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Tambah Karyawan Baru</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="nik" class="form-label">Nik</label>
                            <input type="text" maxlength="16" class="form-control" id="nik" name="nik"
                                placeholder="masukkan nik karyawan">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="masukkan nama karyawan">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label for="level_id" class="form-label">Level Hak Akses</label>
                            <select id="level_id" name="level_id" class="select2 form-select form-select-lg"
                                data-allow-clear="true">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach ($levels as $lev)
                                    <option value="{{ $lev->id }}">{{ $lev->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="text" id="tanggal_lahir" name="tanggal_lahir" class="form-control dob-picker"
                            placeholder="YYYY-MM-DD" value="{{ date('Y-m-d') }}" />
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="jenis_kelamin" id="laki" value="L"
                                    autocomplete="off" checked>
                                <label class="btn btn-outline-secondary w-50" for="laki">Laki-laki</label>

                                <input type="radio" class="btn-check" name="jenis_kelamin" id="perempuan" value="P"
                                    autocomplete="off">
                                <label class="btn btn-outline-secondary w-50" for="perempuan">Perempuan</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="mb-6">
                            <label for="telpon" class="form-label">No Telepon</label>
                            <input type="text" class="form-control" id="telpon" name="telpon"
                                placeholder="masukkan no telepon">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label" for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="text" id="tanggal_masuk" name="tanggal_masuk" class="form-control dob-picker"
                            placeholder="YYYY-MM-DD" value="{{ date('Y-m-d') }}" />
                    </div>
                    <div class="col-6">
                        <div class="mb-6">
                            <label for="gaji" class="form-label">Satuan Gaji</label>
                            <input type="text" class="form-control" id="gaji" name="gaji"
                                placeholder="masukkan satuan gaji">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-412">
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
                    <a href="/app/karyawan" class="btn btn-outline-secondary">
                        <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                        <span class="align-middle">Kembali</span>
                    </a>
                    <div d-flex>
                        <button type="button" id="simpanKaryawan" class="btn btn-primary ms-2">
                            <i class="icon-base bx bx-cloud-upload me-1"></i>
                            <span class="align-middle">Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggle.addEventListener('click', () => {
            password.type = password.type === 'password' ? 'text' : 'password';
            eyeIcon.innerHTML = password.type === 'password' ?
                `<path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
           <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>` :
                `<path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>`;
        });

        $(document).on('change', '.select2', function() {
            const selectedOption = $(this).find('option:selected');
            const bahanData = JSON.parse(selectedOption.val());

            var inputGroup = $(this).closest('.bahan').next('.jumlah');
            inputGroup.find('span.input-group-text').text(bahanData.satuan);
        });

        $(".dob-picker").flatpickr({
            monthSelectorType: "static"
        })

        $("#gaji").on("input", function() {
            let value = $(this).val().replace(/\D/g, "");
            $(this).val(new Intl.NumberFormat("id-ID").format(value));
        });

        $(document).on('click', '#simpanKaryawan', function(e) {
            e.preventDefault();
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            var form = $('#FromKaryawan');
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            text: "Tambahkan Register Karyawan Baru?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Tambahkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload();
                            } else {
                                window.location.href = '/app/karyawan';
                            }
                        });
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
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
