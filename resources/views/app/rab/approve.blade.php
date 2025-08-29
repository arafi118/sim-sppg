@extends('app.layouts.app')

@section('content')
    <div class="card mb-6">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12 mb-6">
                    <label for="periode" class="form-label">Periode</label>
                    <select id="periode" name="periode" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value="">-- Pilih Periode --</option>
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
        </div>
    </div>

    <form action="/app/rab/approve" method="post" id="formApprove">
        @csrf

        <div id="daftarRab">
            <div class="card">
                <div class="card-body"></div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
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

        $(document).on('change', '#periode, #tanggal', function() {
            var periode = $('#periode').val();
            var tanggal = $('#tanggal').val();

            if (!periode || periode.trim() === "") {
                Swal.fire('Peringatan', 'Periode harus diisi!', 'warning');
                return;
            }

            var tanggal_awal = periode.split('_')[0];
            var tanggal_akhir = periode.split('_')[1];

            var params = 'tanggal=' + tanggal_awal + ',' + tanggal_akhir;
            if (tanggal != '-') params = 'tanggal=' + tanggal;

            $.get(`/app/rab/approve-list?${params}`, function(data) {
                var daftarRab = $('#daftarRab');
                daftarRab.empty();

                daftarRab.html(data.view);
            })
        });

        $(document).on('click', '#selectAll', function() {
            var checked = $(this).is(':checked');
            $('input[name="id[]"]').prop('checked', checked);
        })

        $(document).on('click', '#btnApprove', function(e) {
            e.preventDefault();

            approveRab(1)
        })

        $(document).on('click', '#btnReject', function(e) {
            e.preventDefault();

            approveRab(0)
        })

        const approveRab = (approve) => {
            var form = $('#formApprove');
            var actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _token: $('input[name=_token]').val(),
                    periode: $('#periode').val(),
                    approve: approve
                },
                success: function(r) {
                    if (r.success) {
                        Swal.fire("Berhasil!", r.message, "success").then(
                            () => {
                                window.location.reload();
                            });
                    } else {
                        Swal.fire("Gagal!", r.message, "error");
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || "Terjadi kesalahan pada server.";
                    Swal.fire("Gagal!", msg, "error");
                }
            })
        }
    </script>
@endsection
