@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <a href="/app/menu/create" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Menu
                </a>
            </div>
        </div>
        <div class="card-datatable">
            <table class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Name</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const t = document.querySelector(".dt-responsive-child");
        let c;

        if (t) {
            c = setDataTable(t, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/menu",
                },
                columns: [{
                        data: null
                    }, {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "nama",
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    }
                ],
                columnDefs: [{
                    className: "dt-control",
                    orderable: !1,
                    targets: 0,
                    searchable: !1,
                    defaultContent: ""
                }]
            })

            c.on("click", "td.dt-control", e => {
                const row = c.row(e.target.closest("tr"));
                if (row.child.isShown()) return row.child.hide();
                const d = row.data();

                var parentDiv = $('<div>')
                parentDiv.html(`<small class="fw-medium">Bahan - Bahan</small>`)

                var div = $('<div>')
                div.addClass('demo-inline-spacing mt-4')

                var list = $('<ul>')
                list.addClass('list-group list-group-numbered ')
                row.data().resep.forEach(item => {
                    var li = $('<li>')
                    li.addClass('list-group-item')
                    li.text(item.bahan_pangan.nama + ' - ' + item.gramasi + ' ' + item.bahan_pangan.satuan);
                    list.append(li);
                });

                div.append(list)
                parentDiv.append(div)

                row.child(parentDiv).show();
            });
        }

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Menu',
                text: "Apakah Anda yakin ingin menghapus menu ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/app/menu/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                result.message || 'Menu berhasil dihapus.',
                                'success'
                            );
                            c.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menghapus menu.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
