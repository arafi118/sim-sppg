@extends('app.layouts.app')

@section('content')
    <form action="/app/menu/{{ $menu->id }}" method="post" id="formMenu">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Edit Menu</p>
                    <h5 class="mt-1 me-2">{{ $menu->nama }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-6">
                            <label class="form-label" for="nama_menu">Nama Menu</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu"
                                placeholder="Nama Menu" value="{{ $menu->nama }}">
                        </div>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text">Bahan - Bahan</div>
                </div>
                <div class="col-12 form-repeater">
                    <div data-repeater-list="bahan">
                        @foreach ($menu->resep as $resep)
                            @php
                                $nomor = $loop->iteration;
                            @endphp
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-lg-7 col-12 mb-6 bahan">
                                        <label for="form-repeater-{{ $nomor }}-1" class="form-label">Nama
                                            Bahan</label>
                                        <select id="form-repeater-{{ $nomor }}-1" name="nama_bahan"
                                            class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">-- Pilih Bahan --</option>
                                            @foreach ($kelompokPangan as $kp)
                                                @php
                                                    if (count($kp->bahanPangan) == 0) {
                                                        continue;
                                                    }
                                                @endphp
                                                <optgroup label="{{ $kp->nama }}">
                                                    @foreach ($kp->bahanPangan as $bp)
                                                        @php
                                                            $bahan = json_encode($bp);

                                                            $selected =
                                                                $bp->id == $resep->bahan_pangan_id ? 'selected' : '';
                                                        @endphp
                                                        <option value="{{ $bahan }}" {{ $selected }}>
                                                            {{ $bp->nama }} ({{ $bp->satuan }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-12 mb-6 jumlah">
                                        <label for="form-repeater-{{ $nomor }}-2" class="form-label">Jumlah</label>
                                        <div class="input-group input-group-merge">
                                            <input type="number" class="form-control"
                                                id="form-repeater-{{ $nomor }}-2" name="jumlah"
                                                value="{{ $resep->gramasi }}">
                                            <span class="input-group-text">-</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-12 d-flex align-items-end mb-6">
                                        <button type="button" class="btn btn-label-danger w-100" data-repeater-delete>
                                            <i class="icon-base bx bx-x me-1"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-0 d-flex justify-content-between align-items-center">
                        <a href="/app/menu" class="btn btn-outline-secondary">
                            <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                            <span class="align-middle">Kembali</span>
                        </a>

                        <div d-flex>
                            <button type="button" class="btn btn-outline-primary" data-repeater-create>
                                <i class="icon-base bx bx-plus me-1"></i>
                                <span class="align-middle">Tambahkan Bahan</span>
                            </button>

                            <button type="button" id="simpanMenu" class="btn btn-primary ms-2">
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

        $(document).on('change', '.bahan select', function() {
            const selectedOption = $(this).find('option:selected');
            const bahanData = JSON.parse(selectedOption.val());

            var inputGroup = $(this).closest('.bahan').next('.jumlah');
            inputGroup.find('span.input-group-text').text(bahanData.satuan);
        });

        $(document).on('click', '#simpanMenu', function() {
            Swal.fire({
                title: "Simpan Menu?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formMenu').submit();
                }
            })
        });
    </script>
@endsection
