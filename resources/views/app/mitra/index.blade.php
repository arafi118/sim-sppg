@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="row flex-column flex-md-row">
                <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                    <h5 class="card-title mb-0 text-md-start text-center">
                        Daftar Mitra
                    </h5>
                </div>
                <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                    <a href="/app/mitra/create" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Mitra
                    </a>
                </div>
            </div>
        </div>
        <div class="card-datatable">
            <table class="table table-bordered" id="table-mitra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Telpon</th>
                        <th>Bahan Pangan</th>
                        <th>Harga Beli</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = setDataTable('#table-mitra', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '/app/mitra',
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'telpon',
                    name: 'telpon'
                },
                {
                    data: 'bahan_pangan.nama',
                    name: 'bahan_pangan.nama'
                },
                {
                    data: 'harga_beli',
                    name: 'harga_beli',
                    render: function(data) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }
            ],
        });

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Mitra?',
                text: "Data mitra akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/app/mitra/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    result.message || 'mitra berhasil dihapus.',
                                    'success'
                                );
                                table.ajax.reload();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message ||
                                    'Terjadi kesalahan saat menghapus mitra.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.error ||
                                'Terjadi kesalahan saat menghapus mitra.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
