@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Menu
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="kelompokP" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Kelompok Pangan</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusKelompokPangan" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.kelompok-pangan.modal')
@endsection
@section('script')
    <script>
        //buat isi kode
        $('#nama').on('input', function() {
            const initials = $(this).val().split(' ')
                .map(w => w[0]?.toUpperCase() || '')
                .join('');

            $('#kode').val(initials ? '' : '');

            if (initials) {
                $.get(`/app/kelompok-pangan/next-code?initials=${initials}`, res => {
                    $('#kode').val(res.kode);

                });
            }
        });

        const tb = document.querySelector("#kelompokP");
        let cl;

        if (tb) {
            cl = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/kelompok-pangan",
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
            const form = $('#FormKelompokPangan');
            form.trigger('reset');
            form.find('input[name="id_MK"]').val('');
            form.attr('action', '/app/kelompok-pangan');
            form.find('input[name="_method"]').remove();
            $('#formTitle').text("Tambah Kelompok Pangan").css('color', 'green');
            const modal = new bootstrap.Modal(document.getElementById('MK-Pangan'));
            modal.show();
        });

        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();
            const form = $('#FormKelompokPangan');
            $('#id_MK').val(d.id);
            $('#nama').val(d.nama);
            $('#kode').val(d.kode);
            form.attr('action', `/app/kelompok-pangan/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Kelompok Pangan").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('MK-Pangan'));
            modal.show();
        });

        $(document).on('click', '#SimpanKelompokPangan', function(e) {
            e.preventDefault();
            const form = $('#FormKelompokPangan');
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
                        const modalEl = document.getElementById('MK-Pangan');
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
                text: "Data Kelompok Pangan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusKelompokPangan');
                    form.attr('action', `/app/kelompok-pangan/${id}`);
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
