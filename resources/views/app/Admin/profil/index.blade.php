@extends('app.layouts.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            {{-- FORM USER --}}
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-uppercase text-body-secondary">Profile Saya</small>
                        <div class="card mb-4 text-center">
                            <div class="p-4 d-flex flex-column align-items-center">
                                <img src="../../assets/img/avatars/1.png" alt="user image"
                                    class="rounded-circle object-fit-cover mb-3" style="width: 120px; height: 120px;" />
                                <h4 class="mb-1">{{ $user->nama }}</h4>
                                <span class="text-muted">{{ $user->level->nama }}</span>
                            </div>
                        </div>

                        <small class="text-uppercase text-body-secondary">Hak Akses</small>
                        <form id="FormUpdateUser" data-id="{{ $user->id }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="form_type" value="user">

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

            {{-- FORM PROFIL --}}
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <small class="text-uppercase text-body-secondary">Profile Mitra</small>
                        <form id="FormUpdateProfil" data-id="{{ $profil->id }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="form_type" value="profil">

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="mitra_id" class="form-label">Mitra</label>
                                    <select class="form-select select2" id="mitra_id" name="mitra_id">
                                        <option value="">-- Pilih Mitra --</option>
                                        @foreach ($mitra as $m)
                                            <option value="{{ $m->id }}"
                                                {{ $profil->mitra_id == $m->id ? 'selected' : '' }}>
                                                {{ $m->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger" id="msg_mitra_id"></small>
                                </div>
                            </div>

                            @php
                                $fields = ['id_yayasan', 'nama', 'nama_mitra', 'alamat', 'telpon', 'penanggung_jawab'];
                            @endphp

                            @foreach ($fields as $f)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="{{ $f }}"
                                            class="form-label">{{ ucwords(str_replace('_', ' ', $f)) }}</label>
                                        <input type="text" class="form-control" id="{{ $f }}"
                                            name="{{ $f }}" value="{{ $profil->$f ?? '' }}"
                                            placeholder="Masukkan {{ str_replace('_', ' ', $f) }}" />
                                        <small class="text-danger" id="msg_{{ $f }}"></small>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" id="SimpanProfil" class="btn btn-primary">Simpan</button>
                                </div>
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
        $(document).on('change', '.select2', function() {
            const selectedOption = $(this).find('option:selected');
            const bahanData = JSON.parse(selectedOption.val());

            var inputGroup = $(this).closest('.bahan').next('.jumlah');
            inputGroup.find('span.input-group-text').text(bahanData.satuan);
        });

        function ajaxSubmit(form) {
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            let id = form.data('id'); // ambil id sesuai form
            let formData = form.serialize();

            $.ajax({
                url: '/app/profile/' + id,
                type: 'POST', // POST + _method=PUT
                data: formData,
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
