@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <a href="/app/data-pemanfaat/create" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Data Pemanfaat
                </a>
            </div>
        </div>
        <div class="card-datatable">
            <table id="pemanfaat" class="dt-complex-header table table-bordered table-responsive dt-responsive-child">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">Kelompok Pemanfaat</th>
                        <th class="text-center" rowspan="2">Nama Lembaga</th>
                        <th class="text-center" colspan="3">Penanggung Jawab</th>
                        <th class="text-center" rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusPemanfaat" method="post">
        @method('DELETE')
        @csrf

    </form>
@endsection

@section('script')
    <script>
        const tb = document.querySelector("#pemanfaat");
        let cl;

        if (tb) {
            cl = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/data-pemanfaat",
                },
                columns: [{
                        data: 'kelompok_pemanfaat',
                        name: 'kelompok_pemanfaat'
                    },
                    {
                        data: 'nama_lembaga',
                        name: 'nama_lembaga'
                    },
                    {
                        data: 'nama_pj',
                        name: 'nama_pj'
                    },
                    {
                        data: 'jabatan_pj',
                        name: 'jabatan_pj'
                    },
                    {
                        data: 'telpon_pj',
                        name: 'telpon_pj'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="d-inline-flex gap-1">
                                <a href="/app/data-pemanfaat/${data.id}" class="btn btn-sm btn-warning" title="Detail">
                                    Detail
                                </a>
                                <a href="/app/data-pemanfaat/${data.id}/edit" class="btn btn-sm btn-primary" title="Edit">
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
                text: "Data Pemanfaat akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusPemanfaat');
                    form.attr('action', `/app/data-pemanfaat/${id}`);
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
