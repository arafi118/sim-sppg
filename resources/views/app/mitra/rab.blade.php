@extends('app.layouts.app')

@section('content')
    <div class="card mb-6">
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-6">
                    <label for="tanggal" class="form-label">Pilih Tanggal</label>
                    <select id="tanggal" name="tanggal" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value=" ">-- Pilih Tanggal --</option>
                    </select>

                    <input type="hidden" name="tanggal_awal" id="tanggal_awal">
                    <input type="hidden" name="tanggal_akhir" id="tanggal_akhir">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary ms-3" id="btnCetakRAB">
                    <i class="icon-base bx bx-printer me-1"></i> Cetak (RAB)
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#btnCetakRAB').on('click', function() {
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();
            let tanggal = $('#tanggal').val() === 'Satu Periode' ? '-' : $('#tanggal').val();

            let params = 'tanggal=' + tanggal_awal + ',' + tanggal_akhir;
            if (tanggal != '-') params = 'tanggal=' + tanggal;
            window.open('/app/mitra/rab/generate?' + params);
        });


        function setTanggal() {
            var periode = '{{ $periode->tanggal_awal }}_{{ $periode->tanggal_akhir }}';
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
        }

        setTanggal();
    </script>
@endsection
