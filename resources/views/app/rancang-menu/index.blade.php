@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-datatable">
            <table class="table table-bordered" id="table-rancang-menu">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode Ke</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        setDataTable('#table-rancang-menu', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '/app/rancang-menu',
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'periode_ke',
                    name: 'periode_ke'
                },
                {
                    data: 'tanggal_awal',
                    name: 'tanggal_awal'
                },
                {
                    data: 'tanggal_akhir',
                    name: 'tanggal_akhir'
                },
            ],
        });
    </script>
@endsection
