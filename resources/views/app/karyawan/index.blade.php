@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <a href="/app/karyawan/create" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Pengguna
                </a>
            </div>
        </div>
        <div class="card-datatable">
            <table id="karyawan" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Telepon</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusKaryawan" method="post">
        @method('DELETE')
        @csrf

    </form>
@endsection

@section('script')
    <script>
        const tb = document.querySelector("#karyawan");
        let cl;

        if (tb) {
            cl = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/karyawan",
                },
                columns: [{
                        data: 'nama',
                        name: 'nama',
                        render: function(data, type, row) {
                            let src = row.foto_raw;
                            return `
                            <div class="d-flex align-items-center">
                                <img src="${src}" alt="Foto" class="rounded-circle me-2" width="35" height="35">
                                <span>${data}</span>
                            </div>
                        `;
                        }
                    }, {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'telpon',
                        name: 'telpon'
                    },
                    {
                        data: 'level.nama',
                        name: 'level.nama',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data === 'aktif') {
                                return '<span class="badge bg-success">Aktif</span>';
                            } else if (data === 'nonaktif') {
                                return '<span class="badge bg-danger">Nonaktif</span>';
                            } else {
                                return '<span class="badge bg-secondary">Tidak Diketahui</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="d-inline-flex gap-1">
                                <a href="/app/karyawan/${data.id}/edit" class="btn btn-sm btn-primary" title="Edit">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}" title="Hapus">
                                    Hapus
                                </button>
                            </div>`;
                        }
                    }

                ]
            })
        }

        // delete
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data karyawan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusKaryawan');
                    form.attr('action', `/app/karyawan/${id}`);
                    form.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(r) {
                                Swal.fire("Berhasil!", r.message, "success").then(
                                    () => {
                                        cl.ajax.reload();
                                    });
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON?.message ||
                                    "Terjadi kesalahan pada server.";
                                Swal.fire("Gagal!", msg, "error");
                            }
                        });
                    });
                    form.trigger('submit');
                }
            });
        });
    </script>
@endsection
