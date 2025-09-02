@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Kelompok Pemanfaat
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="kelompokF" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusKelompokPemanfaat" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.Kelompok-Pemanfaat.modal')
@endsection
@section('script')
    <script>
        $('#nama').on('input', function() {
            const initials = $(this).val().split(' ')
                .map(w => w[0]?.toUpperCase() || '')
                .join('');

            $('#kode').val(initials ? '' : '');

            if (initials) {
                $.get(`/app/kelompok-pemanfaat/next-code?initials=${initials}`, res => {
                    $('#kode').val(res.kode);

                });
            }
        });

        const tb = document.querySelector("#kelompokF");
        let cl;

        if (tb) {
            cl = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/kelompok-pemanfaat",
                },
                columns: [{
                        data: null,
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'nama',
                        name: 'nama'
                    }, {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="d-inline-flex gap-1">
                                <button class="btn btn-sm btn-primary btnEdit"
                                    data-id="${data.id}"
                                    data-nama="${data.nama}"
                                    data-kode="${data.kode}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}" title="Hapus">
                                    Hapus
                                </button>
                            </div>`;
                        }
                    }
                ],
            });
        }

        $('#btnTambah').click(() => {
            const form = $('#FormKelompokPemanfaat');
            form.trigger('reset');
            form.find('input[name="id_KP"]').val('');
            form.attr('action', '/app/kelompok-pemanfaat');
            form.find('input[name="_method"]').remove();
            $('#formTitle').text("Tambah Kelompok Pemanfaat").css('color', 'green');
            const modal = new bootstrap.Modal(document.getElementById('KP-Pemanfaat'));
            modal.show();
        });

        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();
            const form = $('#FormKelompokPemanfaat');
            $('#id_KP').val(d.id);
            $('#nama').val(d.nama);
            $('#kode').val(d.kode);
            form.attr('action', `/app/kelompok-pemanfaat/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Kelompok Pemanfaat").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('KP-Pemanfaat'));
            modal.show();
        });

        $(document).on('click', '#SimpanKelompokPemanfaat', function(e) {
            e.preventDefault();
            const form = $('#FormKelompokPemanfaat');
            $('small').empty();
            $('.is-invalid').removeClass('is-invalid');
            const actionUrl = form.attr('action');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toast.fire({
                            icon: 'success',
                            title: result.msg
                        });
                        const modalEl = document.getElementById('KP-Pemanfaat');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();
                        if (cl) cl.ajax.reload(null, false);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: result.msg || 'Terjadi kesalahan'
                        });
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    Toast.fire({
                        icon: 'error',
                        title: response?.msg || 'Cek kembali input yang anda masukkan'
                    });
                    if (response && response.errors) {
                        $.each(response.errors, function(field, messages) {
                            const input = $('#' + field);
                            input.addClass('is-invalid');
                            $('#msg_' + field).html(messages[0]);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data Kelompok Pemanfaat akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusKelompokPemanfaat');
                    form.attr('action', `/app/kelompok-pemanfaat/${id}`);
                    form.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(r) {
                                Swal.fire("Berhasil!", r.message, "success").then(
                                    () => {
                                        cl.ajax.reload();
                                    });
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON?.message ||
                                    "Terjadi kesalahan pada server.";
                                Swal.fire("Gagal!", msg, "error");
                            }
                        });
                    });
                    form.trigger('submit');
                }
            });
        });
    </script>
@endsection
