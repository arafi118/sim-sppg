@php
    if (!function_exists('formatWaktu')) {
        function formatWaktu($time)
        {
            [$hours, $minutes, $seconds] = explode(':', $time);
            $result = [];
            if ((int) $hours > 0) {
                $result[] = 'jam ' . (int) $hours;
            }
            if ((int) $minutes > 0) {
                $result[] = ', menit ' . (int) $minutes;
            }
            return implode(' ', $result);
        }
    }
@endphp

@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-12 d-flex">
            <div class="card mb-6 flex-fill">
                <h5 class="card-header">Detail Tahapan</h5>
                <div class="card-body pt-1">
                    <ul class="timeline mb-0">
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">Tahapan</h6>
                                </div>
                                <p class="mb-2">● {{ $tahapan->tahapan }}</p>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">Tanggal Pelaksanaan</h6>
                                </div>
                                <p class="mb-2">
                                    ●
                                    {{ optional($tahapan->penyiapan)->tanggal ? \Carbon\Carbon::parse($tahapan->penyiapan->tanggal)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-success"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">Waktu Mulai</h6>
                                </div>
                                <p class="mb-2">● {{ formatWaktu($tahapan->waktu_mulai) }}</p>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-warning"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">Waktu Selesai</h6>
                                </div>
                                <p class="mb-2">● {{ formatWaktu($tahapan->waktu_selesai) }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-12 d-flex">
            <div class="card mb-6 flex-fill d-flex flex-column">
                <div class="table-responsive flex-fill">
                    <table id="tahapanDetail" class="table datatable-project mb-0">
                        <thead class="border-top">
                            <tr>
                                <th>Nama Pelaksana</th>
                                <th>Alamat</th>
                                <th>Telpon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tahapan->pelaksana as $pelaksana)
                                @if ($pelaksana->karyawan)
                                    <tr>
                                        <td>{{ $pelaksana->karyawan->nama }}</td>
                                        <td>{{ $pelaksana->karyawan->alamat }}</td>
                                        <td>{{ $pelaksana->karyawan->telpon }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-lg-12">
        <div class="card mb-1">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between">
                    <a href="/app/penyiapan-mbg" class="btn btn-outline-secondary">
                        <i class="bx bx-left-arrow-alt me-1"></i>
                        <span>Kembali</span>
                    </a>
                    <div class="d-flex gap-2">
                        <a href="/app/edit-mekanisme/{{ $tahapan->id }}" class="btn btn-outline-primary">
                            <i class="bx bx-edit-alt me-1"></i>
                            <span>Edit Tahapan</span>
                        </a>
                        <button type="submit" class="btn btn-outline-danger btn-delete" data-id="{{ $tahapan->id }}">
                            <i class="bx bx-trash me-1"></i>
                            <span>Hapus Tahapan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="FormHapusTahapan" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#tahapanDetail').DataTable();
        });
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data Tahapan akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then(res => {
                if (res.isConfirmed) {
                    let form = $('#FormHapusTahapan');
                    form.attr('action', `/app/destroy-Mekanisme/${id}`);
                    form.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(r) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer);
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer);
                                    }
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: r.msg || 'Data berhasil dihapus!'
                                }).then(() => {
                                    window.location.href = '/app/penyiapan-mbg';
                                });
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON?.msg ||
                                    "Terjadi kesalahan pada server.";
                                Swal.fire("Gagal!", msg, "error");
                            }
                        });
                    });
                    form.trigger('submit');
                }
            });
        });
    </script>
@endsection
