@extends('app.layouts.app')
@section('content')
    <form action="/app/data-pemanfaat" method="post" id="FormDataPemanfaat">
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">
                    <p class="card-subtitle">Tambah Pemanfaat Baru</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="kelompok_pemanfaat_id" class="form-label">Kelompok Pemanfaat</label>
                            <select id="kelompok_pemanfaat_id" name="kelompok_pemanfaat_id"
                                class="select2 form-select form-select-lg" data-allow-clear="true">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach ($kelompokPemanfaat as $kelompok)
                                    <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="nama_lembaga" class="form-label">Nama Lembaga</label>
                            <input type="text" class="form-control" id="nama_lembaga" name="nama_lembaga"
                                placeholder="masukkan Nama Lembaga">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="nama_pj" class="form-label">Nama Penangung Jawab</label>
                            <input type="text" class="form-control" id="nama_pj" name="nama_pj"
                                placeholder="masukkan nama Penangung Jawab">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="1" placeholder="Alamat Lengkap"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="jabatan_pj" class="form-label">Jabatan Penangung Jawab</label>
                            <input type="text" class="form-control" id="jabatan_pj" name="jabatan_pj"
                                placeholder="jabatan Penangung Jawab">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="telpon_pj" class="form-label">No Telepon Penangung Jawab</label>
                            <input type="text" class="form-control" id="telpon_pj" name="telpon_pj"
                                placeholder="Nomor Telepon Penangung Jawab">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="email_pj" class="form-label">Email Penangung Jawab</label>
                            <input type="text" class="form-control" id="email_pj" name="email_pj"
                                placeholder=" email Penangung Jawab">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-6">
                            <label for="jarak_tempuh" class="form-label">Jarak Tempuh</label>
                            <input type="number" class="form-control" id="jarak_tempuh" name="jarak_tempuh"
                                placeholder="Jarak Tempuh">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="waktu_tempuh_roda_2" class="form-label">Waktu Tempuh Roda 2</label>
                            <input type="time" step="1" class="form-control" id="waktu_tempuh_roda_2"
                                name="waktu_tempuh_roda_2">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="waktu_tempuh_roda_4" class="form-label">Waktu Tempuh Roda 4</label>
                            <input type="time" step="1" class="form-control" id="waktu_tempuh_roda_4"
                                name="waktu_tempuh_roda_4">
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
                            <span class="align-middle">Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $(document).on('change', '.select2', function() {
            const selectedOption = $(this).find('option:selected');
            const bahanData = JSON.parse(selectedOption.val());

            var inputGroup = $(this).closest('.bahan').next('.jumlah');
            inputGroup.find('span.input-group-text').text(bahanData.satuan);
        });

        $(document).on('click', '#simpanDataPemanfaat', function(e) {
            e.preventDefault();
            $('small').html('');
            $('.is-invalid').removeClass('is-invalid');

            var form = $('#FormDataPemanfaat');
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            text: "Tambahkan Data Pemanfaat Baru?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Tambahkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload();
                            } else {
                                window.location.href = '/app/data-pemanfaat';
                            }
                        });
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
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
