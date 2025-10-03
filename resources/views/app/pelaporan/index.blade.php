@extends('app.layouts.app')

@section('content')
    <style>
        body {
            overflow: hidden;
            /* matikan scroll horizontal & vertical */
        }
    </style>
    <div class="card">
        <div class="card-body">
            <form action="/app/pelaporan/preview" method="GET" class="row g-3" target="_blank">
                <input type="hidden" name="laporan" value="buku_besar">
                <input type="hidden" name="action" value="preview">

                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahunan</label>
                    <select name="tahun" class="form-select select2">
                        @for ($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="bulan" class="form-label">Bulanan</label>
                    <select name="bulan" class="form-select select2">
                        <option value="">---</option>
                        @foreach (['01' => 'JANUARI', '02' => 'FEBRUARI', '03' => 'MARET', '04' => 'APRIL', '05' => 'MEI', '06' => 'JUNI', '07' => 'JULI', '08' => 'AGUSTUS', '09' => 'SEPTEMBER', '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DESEMBER'] as $num => $name)
                            <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>
                                {{ $num }}. {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="hari" class="form-label">Harian</label>
                    <select name="hari" class="form-select select2">
                        <option value="">---</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Nama Laporan --}}
                <div class="{{ request('laporan') == 'calk' ? 'col-12' : 'col-md-6' }}">
                    <label for="laporan" class="form-label">Nama Laporan</label>
                    <select name="laporan" id="laporan" class="form-select select2">
                        <option value="">---</option>
                        @foreach ($laporan as $item)
                            <option value="{{ $item->file }}" {{ request('laporan') == $item->file ? 'selected' : '' }}>
                                {{ $item->nama_laporan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sub Laporan --}}
                <div class="{{ request('laporan') == 'calk' ? 'col-12' : 'col-md-6' }}" id="subLaporan">
                    <label for="sub_laporan" class="form-label">Nama Sub Laporan</label>
                    @if (request('laporan') == 'calk')
                        <input type="hidden" name="sub_laporan" value="calk_default">
                        <div class="form-control-plaintext">Catatan atas Laporan Keuangan</div>
                    @else
                        <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                            <option value="">---</option>
                            @if (request('sub_laporan'))
                                <option value="{{ request('sub_laporan') }}" selected>
                                    {{ request('sub_laporan') }}
                                </option>
                            @endif
                        </select>
                    @endif
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" name="action" value="simpan" class="btn btn-danger">Simpan Saldo</button>
                        <button type="submit" name="action" value="excel" class="btn btn-success">Excel</button>
                        <button type="submit" name="action" value="preview" class="btn btn-primary">Preview</button>
                    </div>
                </div>
            </form>


        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('#laporan').on('change', function() {
                let file = $(this).val();
                let tahun = $('select[name="tahun"]').val();
                let bulan = $('select[name="bulan"]').val();

                if (file) {
                    $.get("/app/pelaporan/sub_laporan/" + file + "?tahun=" + tahun + "&bulan=" + bulan,
                        function(result) {
                            $('#subLaporan').html(result);

                            // aktifkan select2 hanya kalau ada select
                            if (file !== 'calk' && $('#subLaporan').find('select').length) {
                                $('#subLaporan').find('select').select2({
                                    width: '100%'
                                });
                            }
                        });
                } else {
                    $('#subLaporan').html('');
                }
            });




        });
    </script>
@endsection
