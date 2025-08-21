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
                        <th>ID</th>
                        <th>Nama</th>
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

    @include('app.Kelompok-Pangan.modal')
@endsection
@section('script')
    <script>
        const tb = document.querySelector("#kelompokP");
        let cl;

        if (tb) {
            cl = new DataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/kelompok-pangan",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="d-inline-flex gap-1">
                                <button class="btn btn-sm btn-primary btnEdit"
                                    data-id="${data.id}"
                                    data-nama="${data.nama}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}" title="Hapus">
                                    Hapus
                                </button>
                            </div>`;
                        }
                    }
                ],
                layout: {
                    topStart: {
                        rowClass: "row mx-3 my-0 justify-content-between",
                        features: [{
                            pageLength: {
                                menu: [7, 10, 25, 50, 100],
                                text: "Show_MENU_entries"
                            }
                        }]
                    },
                    topEnd: {
                        search: {
                            placeholder: ""
                        }
                    },
                    bottomStart: {
                        rowClass: "row mx-3 justify-content-between",
                        features: ["info"]
                    },
                    bottomEnd: {
                        paging: {
                            firstLast: false
                        }
                    }
                },
                scrollX: true,
                language: {
                    paginate: {
                        next: '<i class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-sm"></i>',
                        previous: '<i class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-sm"></i>'
                    }
                }
            });
        }

        setTimeout(() => {
            [{
                    sel: ".dt-buttons .btn",
                    rm: "btn-secondary"
                },
                {
                    sel: ".dt-search .form-control",
                    rm: "form-control-sm",
                    add: "ms-4"
                },
                {
                    sel: ".dt-length .form-select",
                    rm: "form-select-sm"
                },
                {
                    sel: ".dt-layout-table",
                    rm: "row mt-2"
                },
                {
                    sel: ".dt-layout-end",
                    add: "mt-0"
                },
                {
                    sel: ".dt-layout-end .dt-search",
                    add: "mt-md-6 mt-0"
                },
                {
                    sel: ".dt-layout-full",
                    rm: "col-md col-12",
                    add: "table-responsive"
                }
            ].forEach(({
                sel,
                rm,
                add
            }) => {
                document.querySelectorAll(sel).forEach(el => {
                    rm?.split(" ").forEach(cls => el.classList.remove(cls));
                    add?.split(" ").forEach(cls => el.classList.add(cls));
                });
            });
        }, 100);

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
                text: "Data Kelompok Pangan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
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
