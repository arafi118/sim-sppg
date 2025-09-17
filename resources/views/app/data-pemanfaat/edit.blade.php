@extends('app.layouts.app')
@section('content')
    <form action="/app/data-pemanfaat/{{ $dataPemanfaat->id }}" method="POST" id="FormDataPemanfaatEdit">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Edit Data Pemanfaat</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="kelompok_pemanfaat_id" class="form-label">Kelompok Pemanfaat</label>
                            <select id="kelompok_pemanfaat_id" name="kelompok_pemanfaat_id"
                                class="select2 form-select form-select-lg" data-allow-clear="true">
                                <option value="">-- Pilih Kelompok --</option>
                                @foreach ($kelompokPemanfaat as $kelompok)
                                    <option value="{{ $kelompok->id }}"
                                        {{ $kelompok->id == $dataPemanfaat->kelompok_pemanfaat_id ? 'selected' : '' }}>
                                        {{ $kelompok->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="nama_lembaga" class="form-label">Nama Lembaga</label>
                            <input type="text" class="form-control" id="nama_lembaga" name="nama_lembaga"
                                value="{{ $dataPemanfaat->nama_lembaga }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="nama_pj" class="form-label">Nama Penangung Jawab</label>
                            <input type="text" class="form-control" id="nama_pj" name="nama_pj"
                                value="{{ $dataPemanfaat->nama_pj }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="mb-6">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="1">{{ $dataPemanfaat->alamat }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="jabatan_pj" class="form-label">Jabatan Penangung Jawab</label>
                            <input type="text" class="form-control" id="jabatan_pj" name="jabatan_pj"
                                value="{{ $dataPemanfaat->jabatan_pj }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="telpon_pj" class="form-label">No Telepon Penangung Jawab</label>
                            <input type="text" class="form-control" id="telpon_pj" name="telpon_pj"
                                value="{{ $dataPemanfaat->telpon_pj }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="email_pj" class="form-label">Email Penangung Jawab</label>
                            <input type="text" class="form-control" id="email_pj" name="email_pj"
                                value="{{ $dataPemanfaat->email_pj }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="jarak_tempuh" class="form-label">Jarak Tempuh</label>
                            <input type="number" class="form-control" id="jarak_tempuh" name="jarak_tempuh"
                                value="{{ $dataPemanfaat->jarak_tempuh }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="waktu_tempuh_roda_2" class="form-label">Waktu Tempuh Roda 2</label>
                            <input type="time" step="1" class="form-control" id="waktu_tempuh_roda_2"
                                name="waktu_tempuh_roda_2" value="{{ $dataPemanfaat->waktu_tempuh_roda_2 }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="waktu_tempuh_roda_4" class="form-label">Waktu Tempuh Roda 4</label>
                            <input type="time" step="1" class="form-control" id="waktu_tempuh_roda_4"
                                name="waktu_tempuh_roda_4" value="{{ $dataPemanfaat->waktu_tempuh_roda_4 }}">
                        </div>
                    </div>
                </div>
                <div class="mb-0 d-flex justify-content-between align-items-center">
                    <a href="/app/data-pemanfaat" class="btn btn-outline-secondary">
                        <i class="icon-base bx bx-left-arrow-alt me-1"></i>
                        <span class="align-middle">Kembali</span>
                    </a>
                    <div d-flex>
                        <button type="button" id="simpanDataPemanfaat" class="btn btn-primary ms-2">
                            <i class="icon-base bx bx-cloud-upload me-1"></i>
                            <span class="align-middle">Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(".dob-picker").flatpickr({
            monthSelectorType: "static"
        });

        $("#gaji").on("input", function() {
            let value = $(this).val().replace(/\D/g, "");
            $(this).val(new Intl.NumberFormat("id-ID").format(value));
        });

        $(document).on('click', '#simpanDataPemanfaat', function(e) {
            e.preventDefault();
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            var form = $('#FormDataPemanfaatEdit');
            var actionUrl = form.attr('action');

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
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

                        setTimeout(() => {
                            window.location.href = '/app/data-pemanfaat';
                        }, 1500);
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Toast.fire({
                        icon: 'error',
                        title: 'Cek kembali input yang anda masukkan'
                    });
                    if (response && typeof response === 'object') {
                        $.each(response, function(key, message) {
                            $('#' + key).addClass('is-invalid');
                            $('#msg_' + key).html(message[0]);
                        });
                    }
                }
            });
        });
    </script>
@endsection
