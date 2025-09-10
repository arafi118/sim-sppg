@extends('app.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="/app/transaksi" method="post" id="FormTransaksi">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-6">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                <select id="jenis_transaksi" name="jenis_transaksi"
                                    class="select2 form-select form-select-lg">
                                    <option value="">-- Pilih Jenis Transaksi --</option>
                                    @foreach ($jenisTransaksi as $jt)
                                        <option value="{{ $jt->id }}">{{ $jt->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-6">
                                <label for="sumber_dana" class="form-label">Sumber Dana</label>
                                <select id="sumber_dana" name="sumber_dana" class="select2 form-select form-select-lg">
                                    <option value="">Select Value</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="disimpan_ke" class="form-label">Disimpan Ke</label>
                                <select id="disimpan_ke" name="disimpan_ke" class="select2 form-select form-select-lg">
                                    <option value="">Select Value</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-6">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-6">
                                <label for="nominal" class="form-label">Nominal</label>
                                <input type="text" class="form-control" id="nominal" name="nominal" autocomplete="off"
                                    value="0.00" />
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const REKENING = @json($rekening);

        $('#tanggal').flatpickr();
        $("#nominal").maskMoney({
            allowZero: true,
            allowNegative: false,
            precision: 2,
        });

        $(document).on('change', '#jenis_transaksi', function(e) {
            e.preventDefault();

            var jenis_transaksi = $(this).val();

            var label_sumber_dana = 'Sumber Dana';
            var label_disimpan_ke = 'Disimpan Ke';

            var sumber_dana = [];
            var disimpan_ke = [];
            if (jenis_transaksi == '1') {
                sumber_dana = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '2' || item.lev1 == '3' || item.lev1 == '4') {
                        const excludeRekening = [
                            '2.1.04.01',
                            '2.1.04.02',
                            '2.1.04.03',
                            '2.1.02.01',
                            '2.1.03.01'
                        ];

                        if (!excludeRekening.includes(item.kode_akun) && !item.kode_akun.startsWith(
                                '4.1.01')) {
                            acc.push({
                                id: item.id,
                                text: item.kode_akun + '. ' + item.nama_akun
                            });
                        }
                    }

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '1') {
                        acc.push({
                            id: item.id,
                            text: item.kode_akun + '. ' + item.nama_akun
                        });
                    }

                    return acc;
                }, []);

                label_disimpan_ke = 'Disimpan Ke';
            }

            if (jenis_transaksi == '2') {
                sumber_dana = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '1' || item.lev1 == '2') {
                        if (!item.kode_akun.startsWith('2.1.04')) {
                            acc.push({
                                id: item.id,
                                text: item.kode_akun + '. ' + item.nama_akun
                            });
                        }
                    }

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '2' || item.lev1 == '3' || item.lev1 == '5') {
                        acc.push({
                            id: item.id,
                            text: item.kode_akun + '. ' + item.nama_akun
                        });
                    }

                    return acc;
                }, []);

                label_disimpan_ke = 'Keperluan';
            }

            if (jenis_transaksi == '3') {
                sumber_dana = REKENING.reduce((acc, item) => {
                    acc.push({
                        id: item.id,
                        text: item.kode_akun + '. ' + item.nama_akun
                    });

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    acc.push({
                        id: item.id,
                        text: item.kode_akun + '. ' + item.nama_akun
                    });

                    return acc;
                }, []);

                label_disimpan_ke = 'Disimpan Ke';
            }

            setFormSelect2('#sumber_dana', sumber_dana);
            setFormSelect2('#disimpan_ke', disimpan_ke);

            $('label[for="sumber_dana"]').text(label_sumber_dana);
            $('label[for="disimpan_ke"]').text(label_disimpan_ke);
        })

        $(document).on('change', '#sumber_dana, #disimpan_ke', function() {
            var jenis_transaksi = $('#jenis_transaksi').val();
            var sumber_dana = $('#sumber_dana').val();
            var disimpan_ke = $('#disimpan_ke').val();

            var data_sumber_dana = REKENING.find(item => item.id == sumber_dana);
            var data_disimpan_ke = REKENING.find(item => item.id == disimpan_ke);

            var keterangan = '';
            if (data_sumber_dana) {
                if (jenis_transaksi == '1') {
                    keterangan = "Dari " + data_sumber_dana.nama_akun;
                    if (data_disimpan_ke) {
                        keterangan += " ke " + data_disimpan_ke.nama_akun;
                    }
                }

                if (jenis_transaksi == '2') {
                    if (data_sumber_dana.kode_akun.startsWith('1.1.01')) {
                        keterangan = "Bayar ";
                    }

                    if (data_sumber_dana.kode_akun.startsWith('1.1.02')) {
                        keterangan = "Transfer ";
                    }

                    if (data_disimpan_ke) {
                        keterangan += data_disimpan_ke.nama_akun;
                    }
                }

                if (jenis_transaksi == '3') {
                    keterangan = "Pemindahan Saldo " + data_sumber_dana.nama_akun;
                    if (data_disimpan_ke) {
                        keterangan += " ke " + data_disimpan_ke.nama_akun;
                    }
                }
            }

            $('#keterangan').val(keterangan);
        })

        $(document).on('submit', '#FormTransaksi', function(e) {
            e.preventDefault();

            var form = $('#FormTransaksi')
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(r) {
                    if (r.success) {
                        Swal.fire("Berhasil!", r.message, "success")

                        $("#nominal").val('0.00');
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || "Terjadi kesalahan pada server.";
                    Swal.fire("Gagal!", msg, "error");
                }
            })
        })

        function setFormSelect2(target, value = []) {
            var formSelect = $(target);
            formSelect.empty();

            var defaultOption = new Option('Select Value', '', true, true);
            formSelect.append(defaultOption);
            value.forEach(function(opt) {
                var newOption = new Option(opt.text, opt.id, false, false);
                formSelect.append(newOption);
            });

            formSelect.trigger('change');
        }
    </script>
@endsection
