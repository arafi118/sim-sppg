@php
    $tanggal_awal = date('Y-m-d');
    $tanggal_akhir = date('Y-m-d', strtotime('+14 days'));
    if ($periode) {
        $tanggal_awal = $periode->tanggal_awal;
        $tanggal_akhir = $periode->tanggal_akhir;
    }
@endphp

@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="row flex-column flex-md-row">
                <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                    <h5 class="card-title mb-0 text-md-start text-center">
                        Daftar Rancangan Menu
                    </h5>
                </div>
                <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                    <a href="/app/rancang-menu/create" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Rancangan Menu
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <div class="col-md-4 col-12">
                    <input type="text" class="form-control" id="tanggal" />
                </div>
            </div>
        </div>
        <div class="card-datatable">
            <table class="table table-bordered" id="table-rancang-menu">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode Ke</th>
                        <th>Tanggal</th>
                        <th>Kelompok</th>
                        <th>Jumlah</th>
                        <th>Menu</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#tanggal').flatpickr({
            enableTime: false,
            dateFormat: "Y-m-d",
            mode: "range",
            defaultDate: ["{{ $tanggal_awal }}", "{{ $tanggal_akhir }}"],
            locale: {
                rangeSeparator: " - "
            }
        })

        var table = setDataTable('#table-rancang-menu', {
            processing: true,
            serverSide: true,
            ajax: {
                url: '/app/rancang-menu?tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}',
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
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'kelompok_pemanfaat.nama',
                    name: 'kelompok_pemanfaat.nama'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'menu',
                    name: 'menu',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }
            ],
        });

        $(document).on('change', '#tanggal', function() {
            var dates = $(this).val().split(' - ');
            if (dates.length === 2) {
                var startDate = dates[0];
                var endDate = dates[1];

                table.ajax.url('/app/rancang-menu?tanggal_awal=' + startDate + '&tanggal_akhir=' + endDate).load();
            }
        });

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Rancangan Menu?',
                text: "Semua rancangan menu dengan tanggal yang sama akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/app/rancang-menu/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    result.message || 'Rancangan menu berhasil dihapus.',
                                    'success'
                                );
                                table.ajax.reload();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message ||
                                    'Terjadi kesalahan saat menghapus rancangan menu.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.error ||
                                'Terjadi kesalahan saat menghapus rancangan menu.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
