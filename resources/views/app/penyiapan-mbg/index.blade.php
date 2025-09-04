@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Penyiapan Mekanisme
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="Penyiapan" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusPenyiapan" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.penyiapan-mbg.modal')
@endsection
@section('script')
    <script>
        const tb = document.querySelector("#Penyiapan");
        let cl;

        if (tb) {
            cl = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/penyiapan-mbg",
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
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="d-inline-flex gap-1">
                                <a href="/app/create-mekanisme/${data.id}" class="btn btn-sm btn-info title="input mekanisme">
                                    Mekanisme
                                </a>
                                <button class="btn btn-sm btn-warning btnEdit"
                                    data-id="${data.id}"
                                    data-tanggal="${data.tanggal}">
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

        $("#tanggal").flatpickr({
            monthSelectorType: "static",
            appendTo: document.body,
            onOpen: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.style.zIndex = 2000;
            }
        });

        $('#btnTambah').click(() => {
            const form = $('#FormPenyiapanMekanisme');
            form.trigger('reset');
            form.find('input[name="id_PM"]').val('');
            form.attr('action', '/app/penyiapan-mbg');
            form.find('input[name="_method"]').remove();
            $('#formTitle').text("Tambah Tanggal Penyiapan MBG").css('color', 'green');
            const modal = new bootstrap.Modal(document.getElementById('PM-Mekanisme'));
            modal.show();
        });

        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();
            const form = $('#FormPenyiapanMekanisme');
            $('#id_PM').val(d.id);
            $('#tanggal').val(d.tanggal);
            form.attr('action', `/app/penyiapan-mbg/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Tanggal Penyiapan MBG").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('PM-Mekanisme'));
            modal.show();
        });

        $(document).on('click', '#simpanPenyiapan', function(e) {
            e.preventDefault();
            const form = $('#FormPenyiapanMekanisme');
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
                        const modalEl = document.getElementById('PM-Mekanisme');
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
                text: "Data Penyiapan MBG akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusPenyiapan');
                    form.attr('action', `/app/penyiapan-mbg/${id}`);
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
