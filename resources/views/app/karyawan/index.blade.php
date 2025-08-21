@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <a href="/app/karyawan/create" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Menu
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
                        <th>Gaji</th>
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
            cl = new DataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/karyawan",
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'telpon',
                        name: 'telpon'
                    },
                    {
                        data: 'gaji',
                        name: 'gaji',
                        render: function(data) {
                            return new Intl.NumberFormat('id-ID').format(data);
                        }
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
                                    <i class="bx bx-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>`;
                        }
                    }

                ],
                layout: {
                    topStart: {
                        rowClass: "row mx-3 my-0 justify-content-between",
                        features: [{
                            pageLength: {
                                menu: [7, 10, 25, 50, 100],
                                text: "Show_MENU_entries"
                            }
                        }]
                    },
                    topEnd: {
                        search: {
                            placeholder: ""
                        }
                    },
                    bottomStart: {
                        rowClass: "row mx-3 justify-content-between",
                        features: ["info"]
                    },
                    bottomEnd: {
                        paging: {
                            firstLast: false
                        }
                    }
                },
                scrollX: true,
                language: {
                    paginate: {
                        next: '<i class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-sm"></i>',
                        previous: '<i class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-sm"></i>'
                    }
                }
            });
        }

        setTimeout(() => {
            [{
                    sel: ".dt-buttons .btn",
                    rm: "btn-secondary"
                },
                {
                    sel: ".dt-search .form-control",
                    rm: "form-control-sm",
                    add: "ms-4"
                },
                {
                    sel: ".dt-length .form-select",
                    rm: "form-select-sm"
                },
                {
                    sel: ".dt-layout-table",
                    rm: "row mt-2"
                },
                {
                    sel: ".dt-layout-end",
                    add: "mt-0"
                },
                {
                    sel: ".dt-layout-end .dt-search",
                    add: "mt-md-6 mt-0"
                },
                {
                    sel: ".dt-layout-full",
                    rm: "col-md col-12",
                    add: "table-responsive"
                }
            ].forEach(({
                sel,
                rm,
                add
            }) => {
                document.querySelectorAll(sel).forEach(el => {
                    rm?.split(" ").forEach(cls => el.classList.remove(cls));
                    add?.split(" ").forEach(cls => el.classList.add(cls));
                });
            });
        }, 100);

        // delete
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data karyawan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
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
