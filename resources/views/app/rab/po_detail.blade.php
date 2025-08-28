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
                        <th>Kebutuhan (Kg)</th>
                        <th>Jumlah Input</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $totalJumlah = 0;
                        $totalHarga = 0;
                    @endphp
                    @foreach ($po->poDetail as $detail)
                        <tr>
                            <td align="center">{{ $no }}</td>
                            <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                            <td>{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                            <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($detail->jumlah, 2, ',', '.') }} Kg</td>
                            <td class="text-end">
                                {{ $detail->jumlah_input % 1 == 0 ? number_format($detail->jumlah_input, 0, ',', '.') : number_format($detail->jumlah_input, 2, ',', '.') }}
                                Kg
                            </td>
                            <td class="text-end">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $no++;
                            $totalJumlah += $detail->jumlah;
                            $totalHarga += $detail->total_harga;
                        @endphp
                    @endforeach

                    {{-- Baris Total --}}
                    <tr class="fw-bold">
                        <td colspan="6" class="text-center">Total</td>
                        <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ url('/app/rab') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
