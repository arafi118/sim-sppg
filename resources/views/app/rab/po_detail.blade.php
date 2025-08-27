@extends('app.layouts.app')

@section('content')
    <title>{{ $title }}</title>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail PO</h4>
            <p>
                Tanggal {{ \Carbon\Carbon::parse($po->tanggal)->locale('id')->isoFormat('D MMMM Y') }}
            </p>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bahan Pangan</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Sisa Bayar</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $totalHargaSatuan = 0;
                        $totalJumlah = 0;
                        $totalHarga = 0;
                        $totalSisaBayar = 0;
                    @endphp
                    @foreach ($po->poDetail as $detail)
                        <tr>
                            <td align="center">{{ $no }}</td>
                            <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                            <td>{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                            <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($detail->jumlah, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($detail->sisa_bayar, 0, ',', '.') }}</td>
                            <td align="center">{{ ucfirst($detail->status_bayar) }}</td>
                        </tr>
                        @php
                            $no++;
                            $totalHargaSatuan += $detail->harga_satuan;
                            $totalJumlah += $detail->jumlah;
                            $totalHarga += $detail->total_harga;
                            $totalSisaBayar += $detail->sisa_bayar;
                        @endphp
                    @endforeach

                    {{-- Baris Total --}}
                    <tr class="fw-bold">
                        <td colspan="3" class="text-center">Total</td>
                        <td class="text-end">{{ number_format($totalHargaSatuan, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($totalJumlah, 2, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($totalSisaBayar, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>


            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ url('/app/rab') }}" class="btn btn-primary">Kembali</a>
            </div>

        </div>
    </div>
@endsection
