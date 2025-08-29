@extends('app.layouts.app')
@section('content')
    <div class="container-fluid" id="container-wrapper">
        <form action="/transaksi/store" method="post" id="FormTransaksi">
            @csrf
            <input type="hidden" name="clay" id="clay" value="JurnalUmum">

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="alert alert-light" role="alert">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="position-relative mb-3">
                                            <label for="tgl_transaksi_display">Tanggal Transaksi</label>
                                            <input type="text" class="form-control" id="tgl_transaksi_display"
                                                placeholder="Pilih tanggal">
                                            <input type="hidden" name="tgl_transaksi" id="tgl_transaksi"
                                                value="{{ date('Y-m-d') }}">
                                            <small class="text-danger" id="msg_tgl_transaksi"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="position-relative mb-3">
                                            <label for="po_detail_id">Nama Barang</label>
                                            <select class="form-control select2" name="po_detail_id" id="po_detail_id"
                                                style="width: 100%;">
                                                <option value="">-- Pilih Barang --</option>
                                                @foreach ($poDetails as $detail)
                                                    <option value="{{ $detail->id }}"
                                                        data-harga="{{ $detail->harga_satuan }}">
                                                        {{ $detail->bahanPangan->nama ?? '-' }}
                                                    </option>
                                                @endforeach
                                            </select>


                                            <small class="text-danger" id="msg_jenis_transaksi"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="kd_rekening">
                                    <div class="col-md-6">
                                        <div class="position-relative mb-3">
                                            <label for="jumlah">Jumlah</label>
                                            <input type="number" class="form-control" name="jumlah" id="jumlah">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative mb-3">
                                            <label for="harga_satuan">Harga Satuan</label>
                                            <input type="number" class="form-control" name="harga_satuan" id="harga_satuan"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="form_nominal">
                                    <div class="col-md-12">
                                        <div class="position-relative mb-3">
                                            <label for="nominal">Total Harga</label>
                                            <input type="number" class="form-control" name="nominal" id="nominal"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-secondary btn-icon-split" type="submit" id="SimpanTransaksi">
                                    <span class="icon text-white-50">
                                        <i class="bi bi-sign-intersection-fill"></i>
                                    </span>
                                    <span class="text" style="float: right;">Simpan Transaksi</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datepicker
            flatpickr("#tgl_transaksi_display", {
                dateFormat: "d/m/Y",
                defaultDate: new Date(),
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length > 0) {
                        document.getElementById("tgl_transaksi").value =
                            flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    }
                }
            });

            // Ambil harga satuan saat pilih barang
            document.getElementById("po_detail_id").addEventListener("change", function() {
                let harga = this.options[this.selectedIndex].getAttribute("data-harga");
                document.getElementById("harga_satuan").value = harga ? harga : '';
                hitungTotal();
            });

            // Hitung total saat jumlah berubah
            document.getElementById("jumlah").addEventListener("input", function() {
                hitungTotal();
            });

            function hitungTotal() {
                let jumlah = parseFloat(document.getElementById("jumlah").value) || 0;
                let harga = parseFloat(document.getElementById("harga_satuan").value) || 0;
                document.getElementById("nominal").value = jumlah * harga;
            }


        });
    </script>
@endsection
