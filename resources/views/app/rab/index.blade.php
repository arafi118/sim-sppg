@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12 mb-6">
                    <label for="periode" class="form-label">Periode</label>
                    <select id="periode" name="periode" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value="">-- Pilih Kelompok --</option>
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
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="generateRab">
                    <i class="icon-base bx bx-refresh me-1"></i>
                    <span class="align-middle">Generate</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('change', '#periode', function() {
            var periode = $(this).val();
            var $tanggal = $('#tanggal');

            if (!periode) {
                $tanggal.empty().trigger('change');
                return;
            }

            var tanggal_awal = periode.split('_')[0];
            var tanggal_akhir = periode.split('_')[1];

            var awal = new Date(tanggal_awal);
            var akhir = new Date(tanggal_akhir);

            $tanggal.empty();
            $tanggal.append(new Option('Satu Periode', '-', false, false));
            for (var d = new Date(awal); d <= akhir; d.setDate(d.getDate() + 1)) {
                var year = d.getFullYear();
                var month = String(d.getMonth() + 1).padStart(2, '0');
                var day = String(d.getDate()).padStart(2, '0');

                var namaBulan = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                var label = `${day} ${namaBulan[d.getMonth()]} ${year}`;

                var option = new Option(label, `${year}-${month}-${day}`, false, false);
                $tanggal.append(option);
            }
        });

        $(document).on('click', '#generateRab', function() {
            var periode = $('#periode').val();
            var tanggal = $('#tanggal').val();

            if (!periode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Periode harus diisi!',
                });
                return;
            }

            var tanggal_awal = periode.split('_')[0];
            var tanggal_akhir = periode.split('_')[1];
        });
    </script>
@endsection
