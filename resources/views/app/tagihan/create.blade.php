@extends('app.layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="col-12 mb-6">
                <label for="pilih_tanggal" class="form-label">Pilih Tanggal</label>
                <input type="text" id="pilih_tanggal" name="pilih_tanggal" class="form-control" placeholder="Pilih Tanggal" />
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/app/generate-tagihan" method="POST" id="formTagihan">
                @csrf

                <input type="hidden" name="tanggal" id="tanggal" value="">
                <div id="table-tagihan"></div>
            </form>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-sm btn-primary" style="display: none;" id="btnGenerateTagihan">
                    Generate Tagihan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        flatpickr("#pilih_tanggal");

        $(document).on('change', '#pilih_tanggal', function() {
            var tanggal = $(this).val();
            $('#tanggal').val(tanggal);

            $.get('/app/generate-tagihan/tanggal/' + tanggal, function(result) {
                if (result.success) {
                    $('#table-tagihan').html(result.view);

                    if (result.view.length > 0) {
                        $('#btnGenerateTagihan').show();
                    }
                } else {
                    Swal.fire("Gagal!", result.error, "error");
                    $('#table-tagihan').html('');

                    $('#btnGenerateTagihan').hide();
                }
            })
        })

        $(document).on('click', '#btnGenerateTagihan', function() {
            var form = $('#formTagihan');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(r) {
                    if (r.success) {
                        Swal.fire("Berhasil!", r.message, "success").then(
                            () => {
                                window.location.href = '/app/generate-tagihan';
                            });
                    } else {
                        Swal.fire("Gagal!", r.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                }
            })
        })
    </script>
@endsection
