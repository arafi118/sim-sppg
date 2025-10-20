@php
    use App\Utils\Tanggal;
@endphp

@extends('app.layouts.app')

@section('style')
    <style>
        .show-entries select.form-control {
            width: 60px;
            margin: 0 5px;
        }

        .table-filter .filter-group {
            float: right;
            margin-left: 15px;
        }

        .table-filter label {
            font-weight: normal;
            margin-left: 10px;
        }

        .table-filter select,
        .table-filter input {
            display: inline-block;
            margin-left: 5px;
        }

        .table-filter input {
            width: 200px;
            display: inline-block;
        }

        .filter-group select.form-control {
            width: 110px;
        }

        .filter-icon {
            float: right;
            margin-top: 7px;
        }

        .filter-icon i {
            font-size: 18px;
            opacity: 0.7;
        }
    </style>
@endsection

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
        </div>
        <div class="card-datatable">
            <div class="mx-6 mt-6">
                <div class="row">
                    <div class="col-lg-1 col-md-2">
                        <div class="mb-6">
                            <span>Show</span>
                            <select class="form-control select2" id="entriesSelect">
                                <option>5</option>
                                <option selected>10</option>
                                <option>15</option>
                                <option>20</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-11 col-md-10">
                        <div class="row justify-content-end">
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-6">
                                    <label>Periode</label>
                                    <select class="form-control select2" id="periodeSelect">
                                        @foreach ($periode as $p)
                                            <option value="{{ $p->id }}">
                                                {{ Tanggal::tglLatin($p->tanggal_awal) }}
                                                -
                                                {{ Tanggal::tglLatin($p->tanggal_akhir) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4">
                                <div class="mb-6">
                                    <label>Search</label>
                                    <input type="text" class="form-control" id="searchInput"
                                        placeholder="Cari Disini...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <div class="modal fade" id="detailRancangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Rancangan Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailRancanganBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = setDataTable('#table-rancang-menu', {
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
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'data_pemanfaat',
                    name: 'data_pemanfaat'
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
        }, false, false);

        $('#entriesSelect').on('change', function() {
            const value = $(this).val();
            table.page.len(value).draw();
        });

        $('#periodeSelect').on('change', function() {
            table.ajax.url('/app/rancang-menu?periode_id=' + $(this).val()).load();
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
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

        $(document).on('click', '.btn-detail', function(e) {
            e.preventDefault();

            const id = $(this).attr('id');
            $.get('/app/rancang-menu/' + id, function(data) {
                $('#detailRancangan').modal('show');

                $('#detailRancanganBody').html(data.view);
            })
        })
    </script>
@endsection
