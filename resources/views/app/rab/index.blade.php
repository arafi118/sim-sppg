@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Pilih Periode -->
                <div class="col-md-6 col-12 mb-6">
                    <label for="periode" class="form-label">Periode</label>
                    <select id="periode" name="periode" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value=" ">-- Pilih Periode --</option>
                        @foreach ($periode as $p)
                            <option value="{{ $p->tanggal_awal }}_{{ $p->tanggal_akhir }}">
                                Periode Ke {{ $p->periode_ke }} ({{ $p->tanggal_awal }} - {{ $p->tanggal_akhir }})
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- Range Picker -->
                <div class="col-md-6 col-12 mb-6">
                    <label for="tanggal" class="form-label">Pilih Tanggal</label>
                    <input type="text" id="tanggal" class="form-control" placeholder="-- Pilih Tanggal --" readonly />
                </div>
            </div>

            <!-- Input hidden -->
            <input type="hidden" name="tanggal_awal" id="tanggal_awal">
            <input type="hidden" name="tanggal_akhir" id="tanggal_akhir">
            <input type="hidden" name="jenis" id="jenis" value="Periode">

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary" id="btnGeneratePO">
                    <i class="icon-base bx bx-folder me-1"></i> Pemesanan (PO)
                </button>
                <button type="button" class="btn btn-danger ms-3" id="btnCetakRAB">
                    <i class="icon-base bx bx-printer me-1"></i> Cetak (RAB)
                </button>
            </div>
        </div>
    </div>

    <!-- Card untuk PO -->
    <div id="poCard" class="card mt-3" style="display:none;">
        <div class="card-body">
            <h5 class="card-title">Pemesanan (PO)</h5>
            <div id="poTabel"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let fp; // instance flatpickr

            function initFlatpickr(min, max) {
                if (fp) fp.destroy();
                fp = flatpickr("#tanggal", {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    minDate: min,
                    maxDate: max,
                    allowInput: true,
                    locale: {
                        rangeSeparator: "  -  " // <-- ini mengganti 'to' menjadi '-'
                    },
                    onClose: function(selectedDates) {
                        if (selectedDates.length > 0) {
                            let awal = selectedDates[0].toISOString().slice(0, 10);
                            let akhir = selectedDates.length > 1 ? selectedDates[1].toISOString().slice(
                                0, 10) : awal;

                            $('#tanggal_awal').val(awal);
                            $('#tanggal_akhir').val(akhir);

                            // Tampilkan di input sesuai format
                            $('#tanggal').val(awal + ' - ' + akhir);
                        }
                    }
                });
            }


            // Pilih Periode
            $('#periode').on('change', function() {
                let val = $(this).val();
                if (!val || val.trim() === "") {
                    $('#tanggal').val('');
                    $('#tanggal_awal').val('');
                    $('#tanggal_akhir').val('');
                    if (fp) fp.destroy();
                    return;
                }

                let parts = val.split('_');
                let awal = parts[0];
                let akhir = parts[1];

                $('#tanggal_awal').val(awal);
                $('#tanggal_akhir').val(akhir);

                // Default Satu Periode
                $('#tanggal').val('Satu Periode');

                // klik input tanggal untuk memilih tanggal dalam periode
                $('#tanggal').off('focus').on('focus', function() {
                    initFlatpickr(awal, akhir);
                });
            });

            // Generate PO
            $('#btnGeneratePO').on('click', function() {
                let tanggal_awal = $('#tanggal_awal').val();
                let tanggal_akhir = $('#tanggal_akhir').val();
                let tanggal = $('#tanggal').val() === 'Satu Periode' ? '-' : $('#tanggal').val();

                if (!tanggal_awal || !tanggal_akhir) {
                    Swal.fire('Peringatan', 'Periode harus diisi!', 'warning');
                    return;
                }

                $.ajax({
                    url: '/app/rab/po',
                    method: 'GET',
                    data: {
                        tanggal,
                        tanggal_awal,
                        tanggal_akhir
                    },
                    success: function(res) {
                        // ✅ cek kalau response dari controller berupa JSON warning
                        if (res.status === 'warning') {
                            Swal.fire('Peringatan', res.message, 'warning');
                            return;
                        }

                        // ✅ kalau bukan warning → berarti HTML tabel, tampilkan
                        $('#poTabel').html(res);
                        $('#poCard').show();
                        $('html, body').animate({
                            scrollTop: $('#poCard').offset().top
                        }, 500);
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menampilkan PO', 'error');
                    }
                });
            });

            // Cetak RAB
            $('#btnCetakRAB').on('click', function() {
                let tanggal_awal = $('#tanggal_awal').val();
                let tanggal_akhir = $('#tanggal_akhir').val();
                let tanggal = $('#tanggal').val() === 'Satu Periode' ? '-' : $('#tanggal').val();

                let params = 'tanggal=' + tanggal_awal + ',' + tanggal_akhir;
                if (tanggal != '-') params = 'tanggal=' + tanggal;
                window.open('/app/rab/generate?' + params);
            });
        });
    </script>
@endsection
