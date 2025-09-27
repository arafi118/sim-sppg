@php
    if (!function_exists('formatWaktu')) {
        function formatWaktu($time)
        {
            [$hours, $minutes] = array_pad(explode(':', $time), 2, 0);
            $result = [];
            if ((int) $hours > 0) {
                $result[] = (int) $hours . ' jam';
            }
            if ((int) $minutes > 0) {
                $result[] = (int) $minutes . ' menit';
            }
            return implode(' ', $result);
        }
    }
@endphp

@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-5 order-1 order-md-0">
            <div class="card mb-6">
                <h5 class="card-header">Detail Pemanfaat</h5>
                <div class="card-body pt-1">
                    <ul class="timeline mb-0">
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-danger"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">Kelompok</h6>
                                </div>
                                <p class="mb-2">{{ $Pemanfaat->kelompokPemanfaat->nama }} </p>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-success"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Data Pemanfaat</h6>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Nama PJ<br>
                                        &nbsp;&nbsp;&nbsp; <small>{{ $Pemanfaat->nama_pj }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Jabatan PJ<br>
                                        &nbsp;&nbsp;&nbsp; <small>{{ $Pemanfaat->jabatan_pj }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Telepon PJ<br>
                                        &nbsp;&nbsp;&nbsp; <small>{{ $Pemanfaat->telpon_pj }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● E-mail PJ<br>
                                        &nbsp;&nbsp;&nbsp; <small>{{ $Pemanfaat->email_pj }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Jarak Tempuh<br>
                                        &nbsp;&nbsp;&nbsp; <small>{{ $Pemanfaat->jarak_tempuh }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Waktu Tempuh Roda 2<br>
                                        &nbsp;&nbsp;&nbsp;
                                        <small>{{ formatWaktu($Pemanfaat->waktu_tempuh_roda_2) }}</small>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <p class="mb-0">
                                        ● Waktu Tempuh Roda 4<br>
                                        &nbsp;&nbsp;&nbsp;
                                        <small>{{ formatWaktu($Pemanfaat->waktu_tempuh_roda_4) }}</small>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 order-0 order-md-1">
            <div class="card mb-6">
                <h5 class="card-header pt-2 ps-2 pe-2 pb-2  text-center">{{ $Pemanfaat->nama_lembaga }},
                    {{ $Pemanfaat->alamat }}</h5>
            </div>
            <div class="card mb-6">
                <h5 class="card-header my-0 text-md-start text-center">Nama Pemanfaat</h5>
                <div class="table-responsive mb-4">
                    <table id="pemanfaat" class="table datatable-project">
                        <thead class="border-top">
                            <tr>
                                <th>Nama</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Pemanfaat->namaPemanfaat as $data)
                                <tr>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->tempat_lahir }}</td>
                                    <td>{{ $data->tanggal_lahir }}</td>
                                    <td>{{ $data->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 order-0 order-md-1">
            <div class="card mb-1">
                <div class="card-body p-2 ps-2 pe-2 pb-2 pt-2">
                    <a href="/app/data-pemanfaat" class="btn btn-outline-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#pemanfaat').DataTable();
        });
    </script>
@endsection
