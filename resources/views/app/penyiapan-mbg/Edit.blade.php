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
                    <div class="col-12 col-lg-12">
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
                    <div class="col-12 col-lg-4">
                        <div class="mb-6">
                            <label class="form-label" for="icon">Icon</label>
                            <select class="form-select select2" id="icon" name="icon">
                                <option value="bx bx-time" {{ $tahapan->icon == 'bx bx-time' ? 'selected' : '' }}>🕒 Jam
                                </option>
                                <option value="bx bx-restaurant"
                                    {{ $tahapan->icon == 'bx bx-restaurant' ? 'selected' : '' }}>🍳 Masak</option>
                                <option value="bx bx-bowl-hot" {{ $tahapan->icon == 'bx bx-bowl-hot' ? 'selected' : '' }}>🥣
                                    Racik</option>
                                <option value="bx bx-package" {{ $tahapan->icon == 'bx bx-package' ? 'selected' : '' }}>📦
                                    Packing</option>
                                <option value="bx bx-send" {{ $tahapan->icon == 'bx bx-send' ? 'selected' : '' }}>🚚 Kirim
                                </option>
                                <option value="bx bx-brush" {{ $tahapan->icon == 'bx bx-brush' ? 'selected' : '' }}>🧹
                                    Bersih</option>
                                <option value="bx bx-bell" {{ $tahapan->icon == 'bx bx-bell' ? 'selected' : '' }}>🔔
                                    Notifikasi</option>
                                <option value="bx bx-bell-off" {{ $tahapan->icon == 'bx bx-bell-off' ? 'selected' : '' }}>
                                    🔕 Diam</option>
                                <option value="bx bx-home" {{ $tahapan->icon == 'bx bx-home' ? 'selected' : '' }}>🏠 Rumah
                                </option>
                                <option value="bx bx-user" {{ $tahapan->icon == 'bx bx-user' ? 'selected' : '' }}>👤 User
                                </option>
                                <option value="bx bx-group" {{ $tahapan->icon == 'bx bx-group' ? 'selected' : '' }}>👥
                                    Group</option>
                                <option value="bx bx-search" {{ $tahapan->icon == 'bx bx-search' ? 'selected' : '' }}>🔍
                                    Cari</option>
                                <option value="bx bx-edit" {{ $tahapan->icon == 'bx bx-edit' ? 'selected' : '' }}>✏️ Edit
                                </option>
                                <option value="bx bx-trash" {{ $tahapan->icon == 'bx bx-trash' ? 'selected' : '' }}>🗑️
                                    Hapus</option>
                                <option value="bx bx-check" {{ $tahapan->icon == 'bx bx-check' ? 'selected' : '' }}>✔️ Oke
                                </option>
                                <option value="bx bx-x" {{ $tahapan->icon == 'bx bx-x' ? 'selected' : '' }}>❌ Batal
                                </option>
                                <option value="bx bx-like" {{ $tahapan->icon == 'bx bx-like' ? 'selected' : '' }}>👍 Suka
                                </option>
                                <option value="bx bx-dislike" {{ $tahapan->icon == 'bx bx-dislike' ? 'selected' : '' }}>👎
                                    Tidak Suka</option>
                                <option value="bx bx-heart" {{ $tahapan->icon == 'bx bx-heart' ? 'selected' : '' }}>❤️
                                    Favorit</option>
                                <option value="bx bx-star" {{ $tahapan->icon == 'bx bx-star' ? 'selected' : '' }}>⭐ Bintang
                                </option>
                                <option value="bx bx-calendar" {{ $tahapan->icon == 'bx bx-calendar' ? 'selected' : '' }}>
                                    📅 Kalender</option>
                                <option value="bx bx-map" {{ $tahapan->icon == 'bx bx-map' ? 'selected' : '' }}>🗺️ Peta
                                </option>
                                <option value="bx bx-car" {{ $tahapan->icon == 'bx bx-car' ? 'selected' : '' }}>🚗 Mobil
                                </option>
                                <option value="bx bx-train" {{ $tahapan->icon == 'bx bx-train' ? 'selected' : '' }}>🚆
                                    Kereta</option>
                                <option value="bx bx-ship" {{ $tahapan->icon == 'bx bx-ship' ? 'selected' : '' }}>🚢 Kapal
                                </option>
                                <option value="bx bx-money" {{ $tahapan->icon == 'bx bx-money' ? 'selected' : '' }}>💵 Uang
                                </option>
                                <option value="bx bx-credit-card"
                                    {{ $tahapan->icon == 'bx bx-credit-card' ? 'selected' : '' }}>💳 Kartu</option>
                                <option value="bx bx-cart" {{ $tahapan->icon == 'bx bx-cart' ? 'selected' : '' }}>🛒
                                    Belanja</option>
                                <option value="bx bx-lock" {{ $tahapan->icon == 'bx bx-lock' ? 'selected' : '' }}>🔒 Kunci
                                </option>
                                <option value="bx bx-unlock" {{ $tahapan->icon == 'bx bx-unlock' ? 'selected' : '' }}>🔓
                                    Buka</option>
                                <option value="bx bx-cloud" {{ $tahapan->icon == 'bx bx-cloud' ? 'selected' : '' }}>☁️
                                    Cloud</option>
                                <option value="bx bx-cloud-upload"
                                    {{ $tahapan->icon == 'bx bx-cloud-upload' ? 'selected' : '' }}>☁️⬆️ Upload</option>
                                <option value="bx bx-cloud-download"
                                    {{ $tahapan->icon == 'bx bx-cloud-download' ? 'selected' : '' }}>☁️⬇️ Download</option>
                                <option value="bx bx-battery" {{ $tahapan->icon == 'bx bx-battery' ? 'selected' : '' }}>🔋
                                    Baterai</option>
                                <option value="bx bx-alarm" {{ $tahapan->icon == 'bx bx-alarm' ? 'selected' : '' }}>⏰ Alarm
                                </option>
                                <option value="bx bx-volume-full"
                                    {{ $tahapan->icon == 'bx bx-volume-full' ? 'selected' : '' }}>🔊 Volume</option>
                                <option value="bx bx-video" {{ $tahapan->icon == 'bx bx-video' ? 'selected' : '' }}>🎥
                                    Video</option>
                                <option value="bx bx-photo-album"
                                    {{ $tahapan->icon == 'bx bx-photo-album' ? 'selected' : '' }}>📷 Kamera</option>
                                <option value="bx bx-coffee" {{ $tahapan->icon == 'bx bx-coffee' ? 'selected' : '' }}>☕
                                    Kopi</option>
                                <option value="bx bx-dish" {{ $tahapan->icon == 'bx bx-dish' ? 'selected' : '' }}>🍽️
                                    Hidangan</option>
                                <option value="bx bx-fridge" {{ $tahapan->icon == 'bx bx-fridge' ? 'selected' : '' }}>🧊
                                    Pendingin</option>
                                <option value="bx bx-basket" {{ $tahapan->icon == 'bx bx-basket' ? 'selected' : '' }}>🧺
                                    Keranjang</option>
                                <option value="bx bx-leaf" {{ $tahapan->icon == 'bx bx-leaf' ? 'selected' : '' }}>🌿
                                    Organik</option>
                                <option value="bx bx-donate-blood"
                                    {{ $tahapan->icon == 'bx bx-donate-blood' ? 'selected' : '' }}>💉 Donasi</option>
                                <option value="bx bx-wrench" {{ $tahapan->icon == 'bx bx-wrench' ? 'selected' : '' }}>🔧
                                    Perbaikan</option>
                                <option value="bx bx-run" {{ $tahapan->icon == 'bx bx-run' ? 'selected' : '' }}>🏃 Olahraga
                                </option>
                                <option value="bx bx-book" {{ $tahapan->icon == 'bx bx-book' ? 'selected' : '' }}>📖 Buku
                                </option>
                                <option value="bx bx-music" {{ $tahapan->icon == 'bx bx-music' ? 'selected' : '' }}>🎵
                                    Musik</option>
                                <option value="bx bx-flag" {{ $tahapan->icon == 'bx bx-flag' ? 'selected' : '' }}>🚩 Tugas
                                </option>
                                <option value="bx bx-shield" {{ $tahapan->icon == 'bx bx-shield' ? 'selected' : '' }}>🛡️
                                    Proteksi</option>
                            </select>
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
                        <a href="/app/penyiapan-mbg" class="btn btn-outline-secondary">
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
                            window.location.href = '/app/penyiapan-mbg';
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
