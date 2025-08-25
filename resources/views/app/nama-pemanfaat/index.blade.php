@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Pemanfaat
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="NamaP" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Lembaga</th>
                        <th>Nama</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusNamaPemanfaat" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.nama-pemanfaat.modal')
@endsection
@section('script')
    <script>
        const tb = document.querySelector("#NamaP");
        let table;

        if (tb) {
            table = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/nama-pemanfaat",
                },
                columns: [{
                        data: 'Nama_Pemanfaat',
                        name: 'Nama_Pemanfaat'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'tempat_lahir',
                        name: 'tempat_lahir'
                    },
                    {
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                                data-nama="${data.nama}"
                                data-tempat_lahir="${data.tempat_lahir}"
                                data-tanggal_lahir="${data.tanggal_lahir}"
                                data-status="${data.status}"
                                data-data_pemanfaat_id="${data.data_pemanfaat_id}"
                                data-Nama_Pemanfaat="${data.Nama_Pemanfaat}">
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

        $(document).ready(function() {
            $.getJSON('/app/nama-pemanfaat/list', function(data) {
                const select = $('#data_pemanfaat_id');
                select.empty();
                data.forEach(item => {
                    select.append(`<option value="${item.id}">${item.nama_lembaga}</option>`);
                });
            });
        });

        $('#btnTambah').click(() => {
            const form = $('#FormNamaPemanfaat');
            form.trigger('reset');
            form.find('input[name="id_NP"]').val('');

            form.attr('action', `/app/nama-pemanfaat`);
            form.find('input[name="_method"]').remove();

            $('#formTitle').text("Tambah Pemanfaat Baru").css('color', 'green');

            const modal = new bootstrap.Modal(document.getElementById('NP-Pemanfaat'));
            modal.show();
        });

        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();

            const form = $('#FormNamaPemanfaat');

            $('#id_NP').val(d.id);
            $('#nama').val(d.nama);
            $('#tempat_lahir').val(d.tempat_lahir);
            $('#tanggal_lahir').val(d.tanggal_lahir);
            $('#status').val(d.status);

            if (d.data_pemanfaat_id) {
                $('.data_pemanfaat_id').val(d.data_pemanfaat_id).trigger('change');
            } else {
                $('.data_pemanfaat_id').val(null).trigger('change');
            }

            form.attr('action', `/app/nama-pemanfaat/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Pemanfaat").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('NP-Pemanfaat'));
            modal.show();
        });

        $(document).on('click', '#SimpanNamaPemanfaat', function(e) {
            e.preventDefault();
            const form = $('#FormNamaPemanfaat');
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
                        const modalEl = document.getElementById('NP-Pemanfaat');
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
                text: "Data Bahan Pemanfaat akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusNamaPemanfaat');
                    form.attr('action', `/app/nama-pemanfaat/${id}`);
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
