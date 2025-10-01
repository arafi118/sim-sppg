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
                        <th>Aksi</th>
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
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $(document).on('click', '.btn-invoice', function(e) {
            e.preventDefault()

            var id = $(this).attr('data-id');
            window.open('/app/generate-tagihan/' + id + '/invoice', '_blank');
        })

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Tagihan?',
                text: "Tagihan akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/app/generate-tagihan/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    result.message ||
                                    'Tagihan berhasil dihapus.',
                                    'success'
                                );
                                table.ajax.reload();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message ||
                                    'Terjadi kesalahan saat menghapus tagihan.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.error ||
                                'Terjadi kesalahan saat menghapus tagihan.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
