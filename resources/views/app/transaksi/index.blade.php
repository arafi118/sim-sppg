@extends('app.layouts.app')
@section('content')
    <div class="container-fluid" id="container-wrapper">
        <form action="/transaksi/store" method="post" id="FormTransaksi">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="tgl_transaksi_display">Tanggal Transaksi</label>
                    <input type="text" class="form-control" id="tgl_transaksi_display" placeholder="Pilih tanggal">
                    <input type="hidden" name="tanggal_transaksi" id="tgl_transaksi" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label for="bahan_pangan_id">Nama Bahan</label>
                    <select class="form-control select2" name="bahan_pangan_id" id="bahan_pangan_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($bahanpangan as $b)
                            <option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}">{{ $b->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" min="1" required>
                </div>
                <div class="col-md-6">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" readonly>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="nominal">Total Harga</label>
                    <input type="number" class="form-control" name="jumlah_total" id="nominal" readonly>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button class="btn btn-primary" type="submit">Simpan Transaksi</button>
            </div>
        </form>

    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#tgl_transaksi_display", {
                dateFormat: "d/m/Y",
                defaultDate: new Date(),
                allowInput: true,
                onChange: function(selectedDates) {
                    if (selectedDates.length > 0) {
                        document.getElementById("tgl_transaksi").value = flatpickr.formatDate(
                            selectedDates[0], "Y-m-d");
                    }
                }
            });

            const bahanSelect = document.getElementById("bahan_pangan_id");
            const jumlahInput = document.getElementById("jumlah");
            const hargaInput = document.getElementById("harga_jual");
            const nominalInput = document.getElementById("nominal");

            function hitungTotal() {
                let harga = parseFloat(hargaInput.value) || 0;
                let jumlah = parseFloat(jumlahInput.value) || 0;
                nominalInput.value = harga * jumlah;
            }

            bahanSelect.addEventListener("change", function() {
                let harga = this.options[this.selectedIndex].getAttribute("data-harga") || 0;
                hargaInput.value = harga;
                hitungTotal();
            });

            jumlahInput.addEventListener("input", hitungTotal);
        });
    </script>
@endsection
