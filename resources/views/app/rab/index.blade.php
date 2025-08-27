@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12 mb-6">
                    <label for="periode" class="form-label">Periode</label>
                    <select id="periode" name="periode" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value=" ">-- Pilih Periode --</option>
                        @foreach ($periode as $p)
                            <option value="{{ $p->tanggal_awal }}_{{ $p->tanggal_akhir }}">
                                Periode Ke {{ $p->periode_ke }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-12 mb-6">
                    <label for="tanggal" class="form-label">Pilih Tanggal</label>
                    <select id="tanggal" name="tanggal" class="select2 form-select form-select-lg"
                        data-allow-clear="true">
                        <option value=" ">-- Pilih Tanggal --</option>
                    </select>
                </div>
            </div>

            <!-- Input hidden -->
            <input type="hidden" name="tanggal_awal" id="tanggal_awal">
            <input type="hidden" name="tanggal_akhir" id="tanggal_akhir">
            <input type="hidden" name="jenis" id="jenis" value="Periode">

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary" id="btnGeneratePO">
                    <i class="icon-base bx bx-folder me-1"></i>
                    <span class="align-middle">Pemesanan (PO)</span>
                </button>
                <button type="button" class="btn btn-danger ms-3" id="btnCetakRAB">
                    <i class="icon-base bx bx-printer me-1"></i>
                    <span class="align-middle">Cetak (RAB)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Card untuk PO -->
    <div id="poCard" class="card mt-3" style="display:none;">
        <div class="card-body">
            <h5 class="card-title">Pemesanan (PO)</h5>
            <div id="poTabel"></div> <!-- Form PO akan dimuat dari controller -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Generate tanggal berdasarkan periode
            $(document).on('change', '#periode', function() {
                var periode = $(this).val();
                var $tanggal = $('#tanggal');

                if (!periode) {
                    $tanggal.empty().trigger('change');
                    $('#tanggal_awal').val('');
                    $('#tanggal_akhir').val('');
                    return;
                }

                var tanggal_awal = periode.split('_')[0];
                var tanggal_akhir = periode.split('_')[1];

                $('#tanggal_awal').val(tanggal_awal);
                $('#tanggal_akhir').val(tanggal_akhir);

                var awal = new Date(tanggal_awal);
                var akhir = new Date(tanggal_akhir);

                $tanggal.empty();
                $tanggal.append(new Option('Satu Periode', '-', false, false));
                for (var d = new Date(awal); d <= akhir; d.setDate(d.getDate() + 1)) {
                    var year = d.getFullYear();
                    var month = String(d.getMonth() + 1).padStart(2, '0');
                    var day = String(d.getDate()).padStart(2, '0');

                    var namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    var label = `${day} ${namaBulan[d.getMonth()]} ${year}`;
                    $tanggal.append(new Option(label, `${year}-${month}-${day}`, false, false));
                }
            });

            // Generate PO
            $(document).on('click', '#btnGeneratePO', function() {
                var periode = $('#periode').val();
                if (!periode || periode.trim() === "") {
                    Swal.fire('Peringatan', 'Periode harus diisi!', 'warning');
                    return;
                }

                var tanggal = $('#tanggal').val();
                var tanggal_awal = periode.split('_')[0];
                var tanggal_akhir = periode.split('_')[1];

                $.ajax({
                    url: '/app/rab/po',
                    method: 'GET',
                    data: {
                        tanggal: tanggal,
                        tanggal_awal: tanggal_awal,
                        tanggal_akhir: tanggal_akhir
                    },
                    success: function(res) {
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
            $(document).on('click', '#btnCetakRAB', function() {
                var periode = $('#periode').val();
                if (!periode || periode.trim() === "") {
                    Swal.fire('Peringatan', 'Periode harus diisi!', 'warning');
                    return;
                }
                var tanggal = $('#tanggal').val();
                var tanggal_awal = periode.split('_')[0];
                var tanggal_akhir = periode.split('_')[1];
                var params = 'tanggal=' + tanggal_awal + ',' + tanggal_akhir;
                if (tanggal != '-') params = 'tanggal=' + tanggal;
                window.open('/app/rab/generate?' + params);
            });


            $(document).on('click', '#btnSimpanPO', function() {
                var form = $(this).closest('form');
                var data = form.serialize();

                $.ajax({
                    url: '/app/rab/simpanPO',
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'PO berhasil disimpan. Anda ingin melihat detail?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                            customClass: {
                                cancelButton: 'btn btn-danger me-2', // tombol Tidak merah
                                confirmButton: 'btn btn-success' // tombol Ya hijau
                            },
                            buttonsStyling: false // gunakan class Bootstrap
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tombol Ya → ke detail PO
                                window.location.href = '/app/rab/detailPO/' + res.po_id;
                            } else {
                                // Tombol Tidak → kembali ke halaman index PO
                                window.location.href = '/app/rab';
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Gagal', 'PO gagal disimpan', 'error');
                    }
                });
            });




            // Tombol Detail PO
            $(document).on('click', '#btnDetailPO', function() {
                var poId = $(this).data('po-id');
                if (!poId) {
                    Swal.fire('Peringatan', 'PO belum disimpan!', 'warning');
                    return;
                }

                window.location.href = '/app/rab/detailPO/' + poId;
            });


        });
    </script>
@endsection
