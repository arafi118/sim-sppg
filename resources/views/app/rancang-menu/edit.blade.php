@php
    use App\Utils\Tanggal;
    $tanggal = new Tanggal();

    $tanggal_akhir = $rancang_menu->periode->tanggal_akhir;
    $tanggal_awal = $rancang_menu->periode->tanggal_awal;

    $waktu_awal = strtotime($tanggal_awal);
    $waktu_akhir = strtotime($tanggal_akhir);

    $selectedMenu = [];
    foreach ($rancang_menu->rancanganMenu as $rm) {
        $selectedMenu[] = [
            'value' => $rm->menu->nama,
            'id' => $rm->menu->id,
        ];
    }
@endphp

@extends('app.layouts.app')

@section('content')
    <form action="/app/rancang-menu/{{ $rancang_menu->id }}" method="post" id="formRancangMenu">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle" id="card-subtitle">
                        Edit Rancangan Menu Periode ke
                        {{ str_pad($rancang_menu->periode->periode_ke, 2, '0', STR_PAD_LEFT) }}
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
                            value="{{ $rancang_menu->tanggal }}" readonly />
                        <input type="hidden" name="periode_id" id="periode_id" value="{{ $rancang_menu->periode->id }}" />
                    </div>
                </div>

                <div class="divider text-start">
                    <div class="divider-text">Rancang Menu</div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-12 mb-6">
                        <label for="nama_kelompok_pemanfaat" class="form-label">Kelompok Pemanfaat</label>
                        <input id="nama_kelompok_pemanfaat" name="nama_kelompok_pemanfaat" class="form-control list-menu"
                            value="{{ $rancang_menu->kelompokPemanfaat->nama }}" readonly />
                        <input type="hidden" id="kelompok_pemanfaat" name="kelompok_pemanfaat"
                            class="form-control list-menu" value="{{ $rancang_menu->kelompok_pemanfaat_id }}" />
                    </div>
                    <div class="col-lg-9 col-12 mb-6">
                        <label for="pilih-menu" class="form-label">Pilih Menu</label>
                        <input id="pilih-menu" name="menu" class="form-control list-menu"
                            value="{{ json_encode($selectedMenu) }}" />
                    </div>
                </div>
                <hr />

                <div class="mb-0 d-flex justify-content-between align-items-center">
                    <a href="/app/rancang-menu" class="btn btn-outline-secondary">
                        <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                        <span class="align-middle">Kembali</span>
                    </a>

                    <button type="button" id="simpanRancangMenu" class="btn btn-primary ms-2">
                        <i class="icon-base bx bx-cloud-upload me-1"></i>
                        <span class="align-middle">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        const resepMenu = @json($menu);
        const selectMenu = resepMenu.map(m => {
            return {
                value: m.nama,
                id: m.id
            };
        });

        $(document).on('click', '#simpanRancangMenu', function() {
            const form = $('#formRancangMenu');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Rancangan menu berhasil disimpan.',
                        }).then(() => {
                            window.location.href = '/app/rancang-menu';
                        });
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

        setTagify(document.querySelector('#pilih-menu'));
    </script>
@endsection
