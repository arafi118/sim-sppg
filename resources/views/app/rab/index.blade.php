@extends('app.layouts.app')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="/app/pelaporan/preview" method="GET" class="row g-3" target="_blank">
                <div class="col-md-4">
                    <label for="sub_laporan" class="form-label">Nama Sub Laporan</label>
                    <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                        <option value="">---</option>
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

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="action" value="simpan" class="btn btn-danger w-100">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable">
            <table id="BahanP" class="dt-responsive-child table table-bordered">
                <thead>
                    <tr>
                        <th>Kelompok Pangan</th>
                        <th>Nama Bahan Pangan</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>q</td>
                        <td>w</td>
                        <td>d</td>
                        <td>d</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
<script>
    // Inisialisasi DataTable tanpa ajax
    $(document).ready(function() {
        $('#BahanP').DataTable({
            paging: false,
            searching: false,
            info: false
        });
    });
</script>
