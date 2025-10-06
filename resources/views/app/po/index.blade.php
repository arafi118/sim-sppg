@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="row card-header flex-column flex-md-row pb-0">
            <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                <h5 class="card-title mb-0 text-md-start text-center">
                    Daftar PO
                </h5>
            </div>
        </div>
        <div class="card-datatable">
            <table class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Total PO</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="detailPO" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail PO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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
                    url: "/app/daftar-po",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        name: "tanggal",
                        data: "tanggal",
                    },
                    {
                        name: "total_harga",
                        data: "total_harga",
                    },
                    {
                        name: "status_bayar",
                        data: "status_bayar",
                        render: (data) => {
                            if (data.toLowerCase() === 'unpaid') {
                                return '<span class="badge bg-danger">Unpaid</span>';
                            }

                            return '<span class="badge bg-success">Paid</span>';
                        }
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]
            })
        }

        $(document).on('click', '.btn-detail', function(e) {
            e.preventDefault();

            let id = $(this).attr('id');
            $.get('/app/daftar-po/' + id, function(data) {
                $('#detailPO').modal('show');

                $('#detailPO .modal-title').html("Detail PO - " + data.tanggal);
                $('#detailPO .modal-body').html(data.view);
            })
        })
    </script>
@endsection
