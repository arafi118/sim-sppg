@php
    use App\Utils\Tanggal;
    $tanggal = new Tanggal();

    $tanggal_awal = date('Y-m-d');
    $tanggal_akhir = date('Y-m-d', strtotime('+14 days'));
    if ($periode) {
        $tanggal_awal = $periode->tanggal_awal;
        $tanggal_akhir = $periode->tanggal_akhir;
    }

    $waktu_awal = strtotime($tanggal_awal);
    $waktu_akhir = strtotime($tanggal_akhir);
@endphp

@extends('app.layouts.app')

@section('content')
    <form action="/app/rancang-menu" method="post" id="formRancangMenu">
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle" id="card-subtitle">
                        Tambah Rancangan Menu Periode ke {{ str_pad($periode->periode_ke, 2, '0', STR_PAD_LEFT) }}
                    </p>
                    <h5 class="mt-1 me-2" id="card-title">
                        {{ $tanggal->tglLatin($tanggal_awal) }} - {{ $tanggal->tglLatin($tanggal_akhir) }}
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="tanggal" name="tanggal"
                            value="{{ $today }}" />
                        <input type="hidden" name="periode_id" id="periode_id" value="{{ $periode->id }}" />
                    </div>
                </div>

                <div class="divider text-start">
                    <div class="divider-text">Rancang Menu</div>
                </div>
                <div class="col-12 form-repeater">
                    <div data-repeater-list="rancangan">
                        <div data-repeater-item>
                            <div class="row">
                                <div class="col-lg-3 col-12 mb-6">
                                    <label for="form-repeater-1-1" class="form-label">Data Pemanfaat</label>
                                    <select id="form-repeater-1-1" name="data_pemanfaat"
                                        class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="">-- Pilih Pemanfaat --</option>
                                        <option value="besar">Besar</option>
                                        <option value="kecil">Kecil</option>
                                    </select>
                                </div>
                                <div class="col-lg-5 col-12 mb-6">
                                    <label for="form-repeater-1-2" class="form-label">Pilih Menu</label>
                                    <input id="form-repeater-1-2" name="menu" class="form-control list-menu" />
                                </div>
                                <div class="col-lg-2 col-12 mb-6">
                                    <label for="form-repeater-1-3" class="form-label">Jumlah Porsi</label>
                                    <input id="form-repeater-1-3" type="number" value="0" name="porsi"
                                        class="form-control list-porsi" />
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
                        <a href="/app/rancang-menu" class="btn btn-outline-secondary">
                            <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                            <span class="align-middle">Kembali</span>
                        </a>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-primary" data-repeater-create>
                                <i class="icon-base bx bx-plus me-1"></i>
                                <span class="align-middle">Tambahkan Rancangan Menu</span>
                            </button>

                            <button type="button" id="simpanRancangMenu" class="btn btn-primary ms-2">
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
        $('#tanggal').flatpickr()

        var waktu_awal = parseInt('{{ $waktu_awal }}');
        var waktu_akhir = parseInt('{{ $waktu_akhir }}');
        const resepMenu = @json($menu);
        const selectMenu = resepMenu.map(m => {
            return {
                value: m.nama,
                id: m.id
            };
        });

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

                            if ($(el).hasClass("list-menu")) {
                                setTagify(el);
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

        $(document).on('change', '#tanggal', function() {
            const tanggal = $(this).val();
            const timestamp = Math.floor(new Date(tanggal).getTime() / 1000);

            if (timestamp < waktu_awal || timestamp > waktu_akhir) {
                $.get(`/app/rancang-menu/get-periode/${tanggal}`, function(data) {
                    if (data.success) {
                        $('#periode_id').val(data.periode.id);
                        $('#card-title').text(data.title);
                        $('#card-subtitle').text(data.subtitle);

                        waktu_awal = parseInt(data.waktu_awal);
                        waktu_akhir = parseInt(data.waktu_akhir);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Periode tidak ditemukan',
                            text: 'Silakan pilih tanggal yang sesuai dengan periode yang ada.'
                        });
                    }
                });
            }
        });

        $(document).on('click', '#simpanRancangMenu', function() {
            Swal.fire({
                title: "Simpan Menu?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('#formRancangMenu');
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Rancangan menu berhasil disimpan. Tambahkan Rancangan Baru?',
                                    icon: "success",
                                    showCancelButton: true,
                                    confirmButtonText: "Ya, Tambahkan",
                                    cancelButtonText: "Tidak"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('[data-repeater-item]').remove();
                                        $('[data-repeater-create]').trigger('click');

                                        $('#tanggal').val(response.tanggal)
                                            .trigger('change');
                                    } else {
                                        window.location.href = '/app/rancang-menu';
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
                    })
                }
            })
        })

        function setTagify(target) {
            new Tagify(target, {
                whitelist: selectMenu,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-inline",
                    enabled: 0,
                    closeOnSelect: !1
                }
            })
        }

        setTagify(document.querySelector('#form-repeater-1-2'));
    </script>
@endsection
