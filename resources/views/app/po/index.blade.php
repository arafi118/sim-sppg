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

    <div class="modal fade" id="modalBatal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formBatal">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan PO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="po_id" id="batal_po_id">
                        <div class="mb-3">
                            <label for="alasan_batal" class="form-label">Alasan Pembatalan</label>
                            <textarea class="form-control" name="alasan_batal" id="alasan_batal" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Simpan Pembatalan</button>
                    </div>
                </form>
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
                        name: "status",
                        data: "status",
                        render: (data) => {
                            if (data === 'DIBUAT') {
                                return '<span class="badge bg-secondary">Dibuat</span>';
                            } else if (data === 'DIKIRIM') {
                                return '<span class="badge bg-info">Dikirim</span>';
                            } else if (data === 'DITERIMA') {
                                return '<span class="badge bg-success">Diterima</span>';
                            } else if (data === 'DIBATALKAN') {
                                return '<span class="badge bg-danger">Dibatalkan</span>';
                            }

                            return '<span class="badge bg-dark">' + data + '</span>';
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

        $(document).on('click', '.btn-kirim', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            if (confirm('Apakah Anda yakin ingin mengirim PO ini?')) {
                $.post('/app/daftar-po/' + id + '/kirim', {
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        c.ajax.reload();
                    }
                })
            }
        })

        $(document).on('click', '.btn-terima', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            if (confirm('Apakah Anda yakin telah menerima PO ini?')) {
                $.post('/app/daftar-po/' + id + '/terima', {
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        c.ajax.reload();
                    }
                })
            }
        })

        $(document).on('click', '.btn-batal', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $('#batal_po_id').val(id);
            $('#modalBatal').modal('show');
        })

        $('#formBatal').submit(function(e) {
            e.preventDefault();
            let id = $('#batal_po_id').val();
            let data = $(this).serialize();

            $.post('/app/daftar-po/' + id + '/batal', data, function(res) {
                if (res.success) {
                    $('#modalBatal').modal('hide');
                    toastr.success(res.message);
                    c.ajax.reload();
                    $('#formBatal')[0].reset();
                }
            })
        })
    </script>

@endsection
