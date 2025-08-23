@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Periode Masak
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="periode" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusPeriode" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.periode-masak.modal')
@endsection
@section('script')
    <script>
        const tb = document.querySelector("#periode");
        let table;

        if (tb) {
            table = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/periode-masak",
                },
                columns: [{
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
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                        <div class="d-inline-flex gap-1">
                            <button 
                                class="btn btn-sm btn-primary btnEdit"
                                data-id="${data.id}"
                                data-periode_ke="${data.periode_ke}"
                                data-tanggal_awal="${data.tanggal_awal}"
                                data-tanggal_akhir="${data.tanggal_akhir}">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}">
                                Hapus
                            </button>
                        </div>`;
                        }
                    }
                ],
            });
        }

        $(".dob-picker").flatpickr({
            monthSelectorType: "static",
            appendTo: document.body,
            onOpen: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.style.zIndex = 2000;
            }
        });

        $('#btnTambah').click(() => {
            const form = $('#FormPeriodeMasak');
            form.trigger('reset');
            form.find('input[name="id_PM"]').val('');

            form.attr('action', `/app/periode-masak`);
            form.find('input[name="_method"]').remove();

            $('#formTitle').text("Tambah Periode Masak Baru").css('color', 'green');

            const modal = new bootstrap.Modal(document.getElementById('PM-Masak'));
            modal.show();
        });

        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();

            const form = $('#FormPeriodeMasak');

            $('#id_PM').val(d.id);
            $('#periode_ke').val(d.periode_ke);
            $('#tanggal_awal').val(d.tanggal_awal);
            $('#tanggal_akhir').val(d.tanggal_akhir);

            form.attr('action', `/app/periode-masak/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Periode Masak").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('PM-Masak'));
            modal.show();
        });

        $(document).on('click', '#SimpanPeriodeMasak', function(e) {
            e.preventDefault();
            const form = $('#FormPeriodeMasak');
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
                        const modalEl = document.getElementById('PM-Masak');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();
                        if (typeof table !== 'undefined') table.ajax.reload();
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
                text: "Data Periode Masak akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusPeriode');
                    form.attr('action', `/app/periode-masak/${id}`);
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(r) {
                            Swal.fire("Berhasil!", r.message, "success").then(() => {
                                if (table) table.ajax.reload();
                            });
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message ||
                                "Terjadi kesalahan pada server.";
                            Swal.fire("Gagal!", msg, "error");
                        }
                    });
                }
            });
        });
    </script>
@endsection
