@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Dokumentasi
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="dokumentasi" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusDokumentasi" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.dokumentasi-kegiatan.modal')
@endsection

@section('script')
    <script>
        const tb = document.querySelector("#dokumentasi");
        let table;

        if (tb) {
            table = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/dokumentasi-kegiatan",
                },
                columns: [{
                        data: 'gambar',
                        name: 'gambar'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                        <div class="d-inline-flex gap-1">
                    <button class="btn btn-sm btn-primary btnEdit"
                        data-id="${data.id}"
                        data-judul="${data.judul}"
                        data-deskripsi="${data.deskripsi}"
                        data-gambar="${data.gambar_raw}">
                        Edit
                    </button>

                            <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}">
                                Hapus
                            </button>
                        </div>`;
                        }
                    }
                ],
            });
        }

        // validasi upload foto
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })
        $('#gambar').on('change', function() {
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

        // tambah
        $('#btnTambah').click(() => {
            const form = $('#FormDokumentasi');
            form.trigger('reset');
            form.attr('action', "{{ route('dokumentasi-kegiatan.store') }}");
            form.find('input[name="_method"]').remove();
            $('#previewGambar').hide();
            $('#formTitle').text("Tambah Dokumentasi");
            new bootstrap.Modal(document.getElementById('ModalDokumentasi')).show();
        });

        // edit
        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();
            const form = $('#FormDokumentasi');
            form.attr('action', `/app/dokumentasi-kegiatan/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#judul').val(d.judul);
            $('#deskripsi').val(d.deskripsi);

            if (d.gambar) {
                $('#previewGambar').attr('src', d.gambar).show();
                $('#textUpload').addClass('d-none');
            } else {
                $('#previewGambar').hide();
            }

            $('#formTitle').text("Edit Dokumentasi");
            new bootstrap.Modal(document.getElementById('ModalDokumentasi')).show();
        });

        // simpan
        $(document).on('click', '#SimpanDokumentasi', function(e) {
            e.preventDefault();
            const form = $('#FormDokumentasi')[0];
            let formData = new FormData(form);

            $.ajax({
                type: 'POST',
                url: $('#FormDokumentasi').attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        Swal.fire("Berhasil!", result.msg, "success").then(() => {
                            $('#DokumentasiModal').modal('hide');
                            if (table) table.ajax.reload();
                            window.location.href = '/app/dokumentasi-kegiatan';
                        });
                    } else {
                        Swal.fire("Gagal!", result.msg, "error");
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire("Error!", response?.message || "Terjadi kesalahan", "error");
                }
            });
        });


        // hapus
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusDokumentasi');
                    form.attr('action', `/app/dokumentasi-kegiatan/${id}`);
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(r) {
                            Swal.fire("Berhasil!", r.msg, "success");
                            table.ajax.reload(null, false);
                        },
                        error: function() {
                            Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                        }
                    });
                }
            });
        });

        // preview gambar saat upload baru
        $(document).on('change', '#gambar', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewGambar').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
