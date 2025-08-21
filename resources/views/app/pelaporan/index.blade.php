@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/app/pelaporan/preview" method="GET" class="row g-3">
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
                        @foreach ([
            '01' => 'JANUARI',
            '02' => 'FEBRUARI',
            '03' => 'MARET',
            '04' => 'APRIL',
            '05' => 'MEI',
            '06' => 'JUNI',
            '07' => 'JULI',
            '08' => 'AGUSTUS',
            '09' => 'SEPTEMBER',
            '10' => 'OKTOBER',
            '11' => 'NOVEMBER',
            '12' => 'DESEMBER',
        ] as $num => $name)
                            <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>
                                {{ $num }}. {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="hari" class="form-label">Harian</label>
                    <select name="hari" class="form-select select2">
                        <option value="">---</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="laporan" class="form-label">Nama Laporan</label>
                    <select name="laporan" id="laporan" class="form-select select2">
                        <option value="">---</option>
                        @foreach ($laporan as $item)
                            <option value="{{ $item->file }}">{{ $item->nama_laporan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="sub_laporan" class="form-label">Nama Sub Laporan</label>
                    <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                        <option value="">---</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" name="action" value="simpan" class="btn btn-danger">Simpan Saldo</button>
                        <button type="submit" name="action" value="excel" class="btn btn-success">Excel</button>
                        <button type="submit" name="action" value="preview" class="btn btn-primary">Preview</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

<!-- Load Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
