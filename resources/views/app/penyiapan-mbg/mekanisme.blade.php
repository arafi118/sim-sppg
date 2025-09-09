@extends('app.layouts.app')

@section('content')
    <form action="/app/store-mekanisme" method="post" id="formPenyiapanMekanisme">
        @csrf

        <input type="hidden" name="penyiapan_id" value="{{ $tahapan->id }}">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Tambah Menu</p>
                    <h5 class="mt-1 me-2">Buat Menu Baru</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-6">
                            <label class="form-label" for="tahapan">Tahapan</label>
                            <input type="text" class="form-control" id="tahapan" name="tahapan"
                                placeholder="Masukkan Tahapan Penyiapan" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label class="form-label" for="waktu_mulai">Waktu Mulai</label>
                            <input type="time" step="1" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                placeholder="Waktu Mulai" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-6">
                            <label class="form-label" for="waktu_selesai">Waktu Selesai</label>
                            <input type="time" step="1" class="form-control" id="waktu_selesai"
                                name="waktu_selesai" placeholder="Waktu Selesai" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="divider">
                    <div class="divider-text">Pelaksana atau Relawan</div>
                </div>
                <div class="col-12 form-repeater">
                    <div data-repeater-list="pelaksana">
                        <div data-repeater-item>
                            <div class="row">
                                <div class="col-lg-10 col-12 mb-6 pelaksana">
                                    <label for="form-repeater-1-1" class="form-label">Nama Pelaksana</label>
                                    <select id="form-repeater-1-1" name="user_id" class="select2 form-select form-select-lg"
                                        data-allow-clear="true">
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
                    </div>
                    <div class="mb-0 d-flex justify-content-between align-items-center">
                        <a href="/app/penyiapan-mbg" class="btn btn-outline-secondary">
                            <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                            <span class="align-middle">Kembali</span>
                        </a>

                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-primary" data-repeater-create>
                                <i class="icon-base bx bx-plus me-1"></i>
                                <span class="align-middle">Tambahkan Pelaksana</span>
                            </button>

                            <button type="button" id="SimpanMekanisme" class="btn btn-primary ms-2">
                                <i class="icon-base bx bx-cloud-upload me-1"></i>
                                <span class="align-middle">Simpan</span>
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

        $(document).on('change', '.pelaksana select', function() {
            const userId = $(this).val();
        });

        $(document).on('click', '#SimpanMekanisme', function() {
            Swal.fire({
                title: "Simpan Penyiapan?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('#formPenyiapanMekanisme');
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Penyiapan berhasil disimpan. Tambahkan Penyiapan Baru?',
                                    icon: "success",
                                    showCancelButton: true,
                                    confirmButtonText: "Ya, Tambahkan",
                                    cancelButtonText: "Tidak"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('[data-repeater-item]').remove();
                                        $('[data-repeater-create]').trigger('click');
                                    } else {
                                        window.location.href = '/app/penyiapan-mbg';
                                    }
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message ||
                                        'Terjadi kesalahan saat menyimpan.'
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
                    });
                }
            })
        });
    </script>
@endsection
