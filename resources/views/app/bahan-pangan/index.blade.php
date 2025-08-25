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
            <table id="BahanP" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Kelompok Pangan</th>
                        <th>Nama Bahan Pangan</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form id="FormHapusBahanPangan" method="post">
        @method('DELETE')
        @csrf
    </form>

    @include('app.Bahan-Pangan.modal')
@endsection
@section('script')
    <script>
        const tb = document.querySelector("#BahanP");
        let table;

        if (tb) {
            table = setDataTable(tb, {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/app/bahan-pangan",
                },
                columns: [{
                        data: 'kelompok_pangan_nama',
                        name: 'kelompok_pangan_nama'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'satuan',
                        name: 'satuan'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                        render: function(data) {
                            return new Intl.NumberFormat('id-ID').format(data);
                        }
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
                                data-harga_jual="${data.harga_jual}"
                                data-satuan="${data.satuan}"
                                data-kelompok_pangan_id="${data.kelompok_pangan_id}"
                                data-kelompok_pangan_nama="${data.kelompok_pangan?.nama ?? ''}">
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

        // format input harga_jual
        $("#harga_jual").on("input", function() {
            let value = $(this).val().replace(/\D/g, "");
            $(this).val(new Intl.NumberFormat("id-ID").format(value));
        });

        // load kelompok pangan ke select
        $(document).ready(function() {
            $.getJSON('/app/bahan-pangan/list', function(data) {
                const select = $('#kelompok_pangan_id');
                select.empty();
                data.forEach(item => {
                    select.append(`<option value="${item.id}">${item.nama}</option>`);
                });
            });
        });
        // tombol tambah
        $('#btnTambah').click(() => {
            const form = $('#FormBahanPangan');
            form.trigger('reset');
            form.find('input[name="id_BP"]').val('');

            form.attr('action', `/app/bahan-pangan`);
            form.find('input[name="_method"]').remove();

            $('#formTitle').text("Tambah Bahan Pangan").css('color', 'green');

            const modal = new bootstrap.Modal(document.getElementById('BP-Pangan'));
            modal.show();
        });

        // tombol edit
        $(document).on('click', '.btnEdit', function() {
            let d = $(this).data();

            const form = $('#FormBahanPangan');

            $('#id_BP').val(d.id);
            $('#nama').val(d.nama);
            $('#satuan').val(d.satuan);
            $('#harga_jual').val(d.harga_jual);

            if (d.kelompok_pangan_id) {
                $('#kelompok_pangan_id').val(d.kelompok_pangan_id).trigger('change');
            } else {
                $('#kelompok_pangan_id').val(null).trigger('change');
            }

            form.attr('action', `/app/bahan-pangan/${d.id}`);
            form.find('input[name="_method"]').remove();
            form.append('<input type="hidden" name="_method" value="PUT">');
            $('#formTitle').text("Edit Bahan Pangan").css('color', 'goldenrod');
            const modal = new bootstrap.Modal(document.getElementById('BP-Pangan'));
            modal.show();
        });

        // simpan (tambah / edit)
        $(document).on('click', '#SimpanBahanPangan', function(e) {
            e.preventDefault();
            const form = $('#FormBahanPangan');
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
                        const modalEl = document.getElementById('BP-Pangan');
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

        // hapus
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data Bahan Pangan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusBahanPangan');
                    form.attr('action', `/app/bahan-pangan/${id}`);
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
