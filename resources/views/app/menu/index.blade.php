@extends('app.layouts.app')

@section('content')
    <div class="card">
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
            c = new DataTable(t, {
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
                }],
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
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Menu has been deleted.',
                                'success'
                            );
                            c.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the menu.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
