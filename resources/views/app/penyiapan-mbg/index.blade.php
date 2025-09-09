@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <button id="btnTambah" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tanggal Pelayanan
                </button>
            </div>
        </div>
        <div class="card-datatable">
            <table id="Penyiapan" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th></th>
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

    <div class="modal fade" id="modalUserDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="userList"></ul>
                </div>
            </div>
        </div>
    </div>
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
                    url: "/app/penyiapan-mbg"
                },
                columns: [{
                        data: null
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: d => `
                                <div class="d-inline-flex gap-1">
                                    <a href="/app/create-mekanisme/${d.id}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> Tahapan Pelayanan</a>
                                    <button class="btn btn-sm btn-warning btnEdit" data-id="${d.id}" data-tanggal="${d.tanggal}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${d.id}">Hapus</button>
                                </div>`
                    }
                ],
                columnDefs: [{
                    className: "dt-control",
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    defaultContent: ""
                }]
            });

            // Detail
            cl.on("click", "td.dt-control", e => {
                const row = cl.row(e.target.closest("tr")),
                    d = row.data();
                if (row.child.isShown()) return row.child.hide();

                let html = `<small class="fw-medium">Detail Tahapan</small>
                <ul class="list-group list-group mt-2">`;
                if (d.tahapan?.length) {
                    d.tahapan.forEach(t => {
                        html += `
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center 
                                        bg-label-success cursor-pointer btnUserDetail" 
                                    data-id="${t.id}" title="Lihat Pelaksana">
                                <i class="icon-base bx bx-walk icon-18px"></i>
                            </span>
                            <div>
                                <b>${t.tahapan}</b>  
                                <small class="text-muted">(Mulai: ${t.waktu_mulai} - Selesai: ${t.waktu_selesai})</small>
                            </div>
                            <a href="/app/penyiapan-mbg/detail/${t.id}" class="btn btn-sm btn-info ms-auto" title="Edit Tahapan">
                                Detail Tahapan
                            </a>
                        </li>`;
                    });
                } else {
                    html += `<li class="list-group-item text-muted">Belum ada tahapan</li>`;
                }
                html += `</ul>`;
                row.child(html).show();
            });

            // modal pelaksana
            $(document).on("click", ".btnUserDetail", function() {
                const id = $(this).data("id");
                const tahapan = cl.data().toArray().flatMap(r => r.tahapan || []).find(t => t.id == id);
                $("#modalUserDetail .modal-title").text("Pelaksana " + (tahapan?.tahapan || ""));
                let html = "";
                if (tahapan?.pelaksana?.length) {
                    tahapan.pelaksana.forEach(p => {
                        html += `<li class="list-group-item">${p.karyawan?.nama || "Tanpa Nama"}</li>`;
                    });
                } else {
                    html = `<li class="list-group-item text-muted">Belum ada pelaksana</li>`;
                }
                $("#userList").html(html);
                $("#modalUserDetail").modal("show");
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
