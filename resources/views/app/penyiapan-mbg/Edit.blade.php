@extends('app.layouts.app')

@section('content')
    <form action="/app/update-mekanisme/{{ $tahapan->id }}" method="post" id="formEditMekanisme">
        @csrf
        @method('PUT')

        <input type="hidden" name="penyiapan_id" value="{{ $tahapan->penyiapan->id }}">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Edit Menu</p>
                    <h5 class="mt-1 me-2">Edit Menu Tahapan</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-6">
                            <label class="form-label" for="tahapan">Tahapan</label>
                            <input type="text" class="form-control" id="tahapan" name="tahapan"
                                value="{{ $tahapan->tahapan }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-6">
                            <label class="form-label" for="waktu_mulai">Waktu Mulai</label>
                            <input type="time" step="1" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                value="{{ $tahapan->waktu_mulai }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-6">
                            <label class="form-label" for="waktu_selesai">Waktu Selesai</label>
                            <input type="time" step="1" class="form-control" id="waktu_selesai"
                                name="waktu_selesai" value="{{ $tahapan->waktu_selesai }}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="divider">
                    <div class="divider-text">Pelaksana atau Relawan</div>
                </div>
                <div class="col-12 form-repeater">
                    <div data-repeater-list="pelaksana">
                        @forelse ($tahapan->pelaksana as $pel)
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-lg-10 col-12 mb-6 pelaksana">
                                        <label for="form-repeater-1-1" class="form-label">Nama Pelaksana</label>
                                        <select id="form-repeater-1-1" name="user_id"
                                            class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($karyawan as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $pel->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-12 d-flex align-items-end mb-6">
                                        <button type="button" class="btn btn-label-danger w-100" data-repeater-delete>
                                            <i class="icon-base bx bx-x me-1"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        @empty
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-lg-10 col-12 mb-6 pelaksana">
                                        <label for="form-repeater-1-1" class="form-label">Nama Pelaksana</label>
                                        <select id="form-repeater-1-1" name="user_id"
                                            class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($karyawan as $user)
                                                <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-12 d-flex align-items-end mb-6">
                                        <button type="button" class="btn btn-label-danger w-100" data-repeater-delete>
                                            <i class="icon-base bx bx-x me-1"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="/app/penyiapan-mbg/detail/{{ $tahapan->id }}" class="btn btn-outline-secondary">
                            <i class="bx bx-left-arrow-alt me-1"></i>
                            <span>Kembali</span>
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" data-repeater-create>
                                <i class="icon-base bx bx-plus me-1"></i>
                                <span class="align-middle">Tambahkan Pelaksana</span>
                            </button>
                            <button type="button" id="UpdateMekanisme" class="btn btn-primary ms-2">
                                <i class="icon-base bx bx-cloud-upload me-1"></i>
                                <span class="align-middle">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/cleave-zen@0.0.17/dist/cleave-zen.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const repeaterForm = $(".form-repeater");
        if (repeaterForm.length) {
            let groupIndex = 2;
            let fieldIndex = 1;

            repeaterForm.repeater({
                show: function() {
                    let hasSelect2 = false;
                    $(this)
                        .find(".form-control, .form-select")
                        .each((i, el) => {
                            const id = `form-repeater-${groupIndex}-${fieldIndex}`;
                            $(el).attr("id", id);
                            $(this).find(".form-label").eq(i).attr("for", id);
                            if ($(el).hasClass("select2")) {
                                hasSelect2 = true;
                            }
                            fieldIndex++;
                        });
                    groupIndex++;
                    $(this).slideDown();
                    if (hasSelect2) setSelect2();
                },
                hide: function(e) {
                    Swal.fire({
                        title: "Hapus input?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Hapus",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).slideUp(e);
                        }
                    })
                },
            });
        }
        $(document).on('click', '#UpdateMekanisme', function() {
            var form = $('#formEditMekanisme');
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer);
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Tahapan berhasil disimpan.'
                        }).then(() => {
                            window.location.href = '/app/penyiapan-mbg/detail/' + response.data;
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan.',
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Terjadi kesalahan pada server.'
                    });
                }
            });
        });
    </script>
@endsection
