@extends('app.layouts.app')

@section('content')
    <style>
        body {
            overflow: hidden;
        }

        #subLaporan {
            transition: all .2s ease;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <form action="/app/pelaporan/preview" method="GET" target="_blank">
                <div class="row g-3">
                    {{-- Tahun --}}
                    <div class="col-md-4">
                        <label class="form-label">Tahunan</label>
                        <select name="tahun" class="form-select select2">
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Bulan --}}
                    <div class="col-md-4">
                        <label class="form-label">Bulanan</label>
                        <select name="bulan" class="form-select select2">
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
                                <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>{{ $num }}.
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hari --}}
                    <div class="col-md-4">
                        <label class="form-label">Harian</label>
                        <select name="hari" class="form-select select2">
                            <option value="">---</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Nama Laporan & Sub Laporan --}}
                <div id="laporanRow" class="row g-3 align-items-end mt-2">
                    {{-- Nama Laporan --}}
                    <div id="colLaporan" class="col-md-6">
                        <label class="form-label">Nama Laporan</label>
                        <select id="laporan" name="laporan" class="form-select select2">
                            <option value="">---</option>
                            @foreach ($laporan as $item)
                                <option value="{{ $item->file }}" {{ request('laporan') == $item->file ? 'selected' : '' }}>
                                    {{ $item->nama_laporan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sub Laporan --}}
                    <div id="subLaporan" class="col-md-6">
                        <label class="form-label">Nama Sub Laporan</label>
                        @if (request('laporan') == 'calk')
                            <input type="hidden" name="sub_laporan" value="calk_default">
                            <div class="form-control-plaintext">Catatan atas Laporan Keuangan</div>
                        @else
                            <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                                <option value="">---</option>
                                @if (request('sub_laporan'))
                                    <option value="{{ request('sub_laporan') }}" selected>{{ request('sub_laporan') }}
                                    </option>
                                @endif
                            </select>
                        @endif
                    </div>
                </div>

                {{-- Tombol --}}
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
        $(function() {
            $('.select2').select2({
                width: '100%'
            });

            function adjustLayout(file) {
                const laporanCol = $('#colLaporan');
                const subCol = $('#subLaporan');
                const row = $('#laporanRow');

                if (file === 'calk') {
                    row.removeClass('row g-3 align-items-end').addClass('d-flex flex-column');
                    laporanCol.removeClass().addClass('col-12 mb-2');
                    subCol.removeClass().addClass('col-12');
                } else {
                    row.removeClass('d-flex flex-column').addClass('row g-3 align-items-end');
                    laporanCol.removeClass().addClass('col-md-6');
                    subCol.removeClass().addClass('col-md-6');
                }
            }

            function initQuill() {
                const $ed = $('#editor');
                if ($ed.length && typeof Quill !== 'undefined') {
                    $ed.siblings('.ql-toolbar,.ql-container').remove();
                    const q = new Quill('#editor', {
                        theme: 'snow'
                    });
                    q.on('text-change', () => $('input[name="sub_laporan"]').val(q.root.innerHTML));
                }
            }

            $('#laporan').on('change', function() {
                const file = $(this).val(),
                    th = $('select[name="tahun"]').val(),
                    bl = $('select[name="bulan"]').val();
                adjustLayout(file);
                if (file) {
                    $.get(`/app/pelaporan/sub_laporan/${file}?tahun=${th}&bulan=${bl}`, res => {
                        $('#subLaporan').html(res);
                        $('#subLaporan').find('select').select2({
                            width: '100%'
                        });
                        if ($('#subLaporan #editor').length) initQuill();
                    });
                } else $('#subLaporan').html('');
            });

            const initFile = $('#laporan').val();
            adjustLayout(initFile);
            if ($('#subLaporan #editor').length) initQuill();
        });
    </script>
@endsection
