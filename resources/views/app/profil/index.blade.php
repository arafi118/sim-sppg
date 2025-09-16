@extends('app.layouts.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-uppercase text-body-secondary">Profile Saya</small>
                        <form id="FormUpdateUser" data-id="{{ $user->id }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="user">

                            <div class="card mb-4 text-center">
                                <div class="p-4 d-flex flex-column align-items-center">
                                    <label for="foto" class="position-relative" style="cursor:pointer;">
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-secondary"
                                            style="width: 120px; height: 120px; overflow: hidden;">
                                            <img id="preview-image"
                                                src="{{ $user->foto ? asset('storage/' . $user->foto) : '/assets/img/landing-page/default.png' }}"
                                                alt="user image" class="w-100 h-100 rounded-circle"
                                                style="object-fit: cover;" />
                                            <div class="position-absolute bottom-0 end-0 bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:30px; height:30px; font-size:16px;">
                                                ✎
                                            </div>
                                        </div>
                                    </label>
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                                    <h4 class="mb-1 mt-2">{{ $user->nama }}</h4>
                                    <span class="text-muted">{{ $user->level->nama }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ $user->username }}" placeholder="Masukkan username" />
                                <small class="text-danger" id="msg_username"></small>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password" />
                                <small class="text-danger" id="msg_password"></small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Masukkan password lagi" />
                                <small class="text-danger" id="msg_password_confirmation"></small>
                            </div>

                            <div class="text-end">
                                <button type="submit" id="SimpanUser" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <small class="text-uppercase text-body-secondary">Profil Mitra</small>
                        <form id="FormUpdateProfil" data-id="{{ $profil->id }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="profil">
                            <input type="hidden" name="mitra_id" value="{{ $profil->mitra_id }}">

                            <div class="mb-3">
                                <label for="id_yayasan" class="form-label">kode Yayasan</label>
                                <input type="text" class="form-control" id="id_yayasan" name="id_yayasan"
                                    value="{{ $profil->id_yayasan ?? '' }}" placeholder="Masukkan ID Yayasan">
                                <small class="text-danger" id="msg_id_yayasan"></small>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $profil->nama ?? '' }}" placeholder="Masukkan Nama">
                                <small class="text-danger" id="msg_nama"></small>
                            </div>

                            <div class="mb-3">
                                <label for="nama_mitra" class="form-label">Nama Mitra</label>
                                <input type="text" class="form-control" id="nama_mitra" name="nama_mitra"
                                    value="{{ $profil->nama_mitra ?? '' }}" placeholder="Masukkan Nama Mitra">
                                <small class="text-danger" id="msg_nama_mitra"></small>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat"
                                    value="{{ $profil->alamat ?? '' }}" placeholder="Masukkan Alamat">
                                <small class="text-danger" id="msg_alamat"></small>
                            </div>

                            <div class="mb-3">
                                <label for="telpon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telpon" name="telpon"
                                    value="{{ $profil->telpon ?? '' }}" placeholder="Masukkan Telepon">
                                <small class="text-danger" id="msg_telpon"></small>
                            </div>

                            <div class="mb-3">
                                <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab"
                                    value="{{ $profil->penanggung_jawab ?? '' }}"
                                    placeholder="Masukkan Penanggung Jawab">
                                <small class="text-danger" id="msg_penanggung_jawab"></small>
                            </div>

                            <div class="text-end">
                                <button type="submit" id="SimpanProfil" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        const input = document.getElementById('foto');
        const preview = document.getElementById('preview-image');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        function ajaxSubmit(form) {
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            let id = form.data('id');
            let formData = new FormData(form[0]);

            $.ajax({
                url: '/app/profile/' + id,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    Swal.fire({
                        title: 'Cek input Anda!',
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    if (res && res.errors) {
                        $.each(res.errors, function(key, messages) {
                            $('#' + key).addClass('is-invalid');
                            $('#msg_' + key).html(messages[0]);
                        });
                    }
                }
            });
        }

        $('#SimpanUser').click(function(e) {
            e.preventDefault();
            ajaxSubmit($('#FormUpdateUser'));
        });
        $('#SimpanProfil').click(function(e) {
            e.preventDefault();
            ajaxSubmit($('#FormUpdateProfil'));
        });
    </script>
@endsection
