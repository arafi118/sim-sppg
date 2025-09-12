@extends('app.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="/app/transaksi" method="post" id="FormTransaksi">
                        @csrf

                        <input type="hidden" name="transaksi" id="transaksi" value="jurnal_umum">
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

                        <div class="row" id="form-jurnal-umum">
                            <div class="col-12 mb-6">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan_transaksi" name="jurnal_umum[keterangan]" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-6">
                                <label for="nominal" class="form-label">Nominal</label>
                                <input type="text" class="form-control nominal" id="nominal"
                                    name="jurnal_umum[nominal]" autocomplete="off" value="0.00" />
                            </div>
                        </div>

                        <div class="row" id="form-beli-inventaris" style="display: none;">
                            <input type="hidden" id="jenis_inventaris" name="beli_inventaris[jenis_inventaris]">
                            <input type="hidden" id="kategori_inventaris" name="beli_inventaris[kategori_inventaris]">
                            <div class="col-md-12 mb-6">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang"
                                    name="beli_inventaris[nama_barang]" autocomplete="off" />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control nominal" id="harga_satuan"
                                    name="beli_inventaris[harga_satuan]" autocomplete="off" value="0.00" />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="umur_ekonomis" class="form-label">Umur Eko. (bulan)</label>
                                <input type="number" class="form-control" id="umur_ekonomis"
                                    name="beli_inventaris[umur_ekonomis]" autocomplete="off" value="0" />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="jumlah_unit" class="form-label">Jumlah Unit</label>
                                <input type="number" class="form-control" id="jumlah_unit"
                                    name="beli_inventaris[jumlah_unit]" autocomplete="off" value="0" />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="harga_perolehan" class="form-label">Harga Perolehan</label>
                                <input type="text" class="form-control nominal" id="harga_perolehan"
                                    name="beli_inventaris[harga_perolehan]" autocomplete="off" value="0.00" />
                            </div>
                        </div>

                        <div class="row" id="form-hapus-inventaris" style="display: none;">
                            <div class="col-md-12 mb-6">
                                <label for="daftar_barang" class="form-label">Daftar Barang</label>
                                <select id="daftar_barang" name="hapus_inventaris[daftar_barang]"
                                    class="select2 form-select form-select-lg">
                                    <option value="">Select Value</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="alasan" class="form-label">Alasan</label>
                                <select id="alasan" name="hapus_inventaris[alasan]"
                                    class="select2 form-select form-select-lg">
                                    <option value="">Select Value</option>
                                    <option value="jual">Jual</option>
                                    <option value="hapus">Hapus</option>
                                    <option value="hilang">Hilang</option>
                                    <option value="revaluasi">Revaluasi</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="jumlah_unit_inventaris" class="form-label">Jumlah Unit</label>
                                <input type="number" class="form-control" id="jumlah_unit_inventaris"
                                    name="hapus_inventaris[jumlah_unit_inventaris]" autocomplete="off" value="0"
                                    max="0" min="0" />
                            </div>

                            <div class="col-md-12 mb-6" id="input-nilai-buku">
                                <label for="nilai_buku" class="form-label">Nilai Buku</label>
                                <input type="text" class="form-control nominal" id="nilai_buku"
                                    name="hapus_inventaris[nilai_buku]" readonly autocomplete="off" value="0.00" />
                            </div>
                            <div class="col-md-6 mb-6" id="input-harga-jual" style="display: none;">
                                <label for="harga_jual" class="form-label">Harga Jual</label>
                                <input type="text" class="form-control nominal" id="harga_jual"
                                    name="hapus_inventaris[harga_jual]" autocomplete="off" value="0.00" />
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
        const INVENTARIS = {
            id: 0,
            jumlah: 0,
            nilai_buku: 0
        }

        $('#tanggal').flatpickr();

        $(document).on('change', '#tanggal', function() {
            var tanggal = $(this).val();

            ambilDaftarInventaris(tanggal);
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
                                text: (item.kode_akun + '. ' + item.nama_akun)
                            });
                        }
                    }

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '1') {
                        acc.push({
                            id: item.id,
                            text: (item.kode_akun + '. ' + item.nama_akun)
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
                                text: (item.kode_akun + '. ' + item.nama_akun)
                            });
                        }
                    }

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    if (item.lev1 == '2' || item.lev1 == '3' || item.lev1 == '5') {
                        acc.push({
                            id: item.id,
                            text: (item.kode_akun + '. ' + item.nama_akun)
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
                        text: (item.kode_akun + '. ' + item.nama_akun)
                    });

                    return acc;
                }, [])

                disimpan_ke = REKENING.reduce((acc, item) => {
                    acc.push({
                        id: item.id,
                        text: (item.kode_akun + '. ' + item.nama_akun)
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

            $('#keterangan_transaksi').val(keterangan);

            if (data_sumber_dana && data_disimpan_ke) {
                handleFormTransaksi(data_sumber_dana, data_disimpan_ke, jenis_transaksi);
            }
        })

        $(document).on('change', '#daftar_barang', function() {
            var inventaris = $(this).val().split('#');

            INVENTARIS.id = inventaris[0];
            INVENTARIS.jumlah = inventaris[1];
            INVENTARIS.nilai_buku = inventaris[2];

            $('#jumlah_unit_inventaris').attr('max', INVENTARIS.jumlah);

            $('#jumlah_unit_inventaris').val('0');
            $('#nilai_buku').val(numberFormat(INVENTARIS.nilai_buku));
            $('#harga_jual').val(numberFormat(INVENTARIS.nilai_buku));
        })

        $(document).on('change', '#jumlah_unit_inventaris', function() {
            var jumlah_unit = $(this).val();
            if (Number(jumlah_unit) > Number(INVENTARIS.jumlah)) {
                $('#jumlah_unit_inventaris').val(INVENTARIS.jumlah);
            }

            if (Number(jumlah_unit) < 0) {
                $('#jumlah_unit_inventaris').val(0);
            }
        })

        $(document).on('change', '#alasan', function() {
            var alasan = $(this).val();

            if (alasan == 'jual' || alasan == 'revaluasi') {
                $('#input-nilai-buku').removeClass('col-md-12').addClass('col-md-6');
                $('#input-harga-jual').show();
            } else {
                $('#input-nilai-buku').removeClass('col-md-6').addClass('col-md-12');
                $('#input-harga-jual').hide();
            }
        })

        $(document).on('input', '#harga_satuan, #jumlah_unit', function() {
            var harga_satuan = $('#harga_satuan').val() || 0;
            var jumlah_unit = $('#jumlah_unit').val() || 0;

            if (harga_satuan != 0) {
                harga_satuan = numberUnformat(harga_satuan);
            }

            var harga_perolehan = harga_satuan * jumlah_unit;
            $('#harga_perolehan').val(numberFormat(harga_perolehan));
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

        function handleFormTransaksi(sumber_dana, disimpan_ke, jenis_transaksi) {
            var form_jurnal_umum = $('#form-jurnal-umum');
            var form_beli_inventaris = $('#form-beli-inventaris');
            var form_hapus_inventaris = $('#form-hapus-inventaris');

            if (sumber_dana.kode_akun.startsWith('1.2.01') && disimpan_ke.kode_akun.startsWith('5.3.02.01') &&
                jenis_transaksi == '2') {
                form_jurnal_umum.hide();
                form_beli_inventaris.hide();
                form_hapus_inventaris.show();

                $('#jenis_inventaris').val('ati');
                if (sumber_dana.kode_akun.startsWith('1.2.03')) {
                    $('#jenis_inventaris').val('atb');
                }

                $('#kategori_inventaris').val(sumber_dana.kode_akun.split(".").pop());
                $('#transaksi').val('hapus_inventaris');

                ambilDaftarInventaris($('#tanggal').val());
                return;
            }

            if (disimpan_ke.kode_akun.startsWith('1.2.01') || disimpan_ke.kode_akun.startsWith('1.2.03')) {
                form_jurnal_umum.hide();
                form_beli_inventaris.show();
                form_hapus_inventaris.hide();

                $('#jenis_inventaris').val('ati');
                if (disimpan_ke.kode_akun.startsWith('1.2.03')) {
                    $('#jenis_inventaris').val('atb');
                }

                $('#kategori_inventaris').val(disimpan_ke.kode_akun.split(".").pop());
                $('#transaksi').val('beli_inventaris');
                return;
            }

            form_jurnal_umum.show();
            form_beli_inventaris.hide();
            form_hapus_inventaris.hide();

            $('#transaksi').val('jurnal_umum');
            return;
        }

        function ambilDaftarInventaris(tanggal) {
            var jenis = $('#jenis_inventaris').val();
            var kategori = $('#kategori_inventaris').val();

            if (!jenis || !kategori) {
                return;
            }

            $.ajax({
                url: `/app/transaksi/daftar-inventaris`,
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    jenis: jenis,
                    kategori: kategori
                },
                success: function(result) {
                    const options = result.map((item) => {
                        const id = item.id + "#" + item.jumlah + "#" + item.nilai_buku;
                        const text = item.id + ". " + item.nama + " (" + item.jumlah +
                            " unit x " + numberFormat(item.harga_satuan) + ") | NB. " +
                            numberFormat(item.nilai_buku);

                        return {
                            id: id,
                            text: text
                        }
                    })

                    setFormSelect2('#daftar_barang', options);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || "Terjadi kesalahan pada server.";
                    Swal.fire("Gagal!", msg, "error");
                }
            })
        }

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
