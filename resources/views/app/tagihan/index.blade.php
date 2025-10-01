@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <a href="/app/generate-tagihan/create" class="btn btn-primary">Buat Tagihan</a>
            </div>
            <table class="table table-striped" id="table-tagihan">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = setDataTable('#table-tagihan', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '/app/generate-tagihan',
                type: 'GET',
            },

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: (data) => {
                        if (data === 'UNPAID') {
                            return '<span class="badge bg-warning">Unpaid</span>';
                        } else {
                            return '<span class="badge bg-success">Paid</span>';
                        }
                    }
                },
            ],
        });
    </script>
@endsection
