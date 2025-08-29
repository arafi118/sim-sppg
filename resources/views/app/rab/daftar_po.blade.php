@extends('app.layouts.app')

@section('content')
    <title>{{ $title }}</title>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>

            @foreach ($pos as $po)
                <div class="mb-4">
                    <p>Tanggal PO:
                        {{ \Carbon\Carbon::parse($po->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <table class="table table-bordered">
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
                                @if ($detail->jumlah_input > 0)
                                    <tr>
                                        <td align="center">{{ $no }}</td>
                                        <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                                        <td align="center">{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                                        <td align="center">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td align="center">{{ number_format($detail->jumlah, 2, ',', '.') }} Kg</td>
                                        <td align="center">
                                            {{ $detail->jumlah_input % 1 == 0
                                                ? number_format($detail->jumlah_input, 0, ',', '.')
                                                : number_format($detail->jumlah_input, 2, ',', '.') }}
                                            Kg
                                        </td>
                                        <td class="text-end">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @php
                                        $no++;
                                        $totalJumlah += $detail->jumlah;
                                        $totalHarga += $detail->total_harga;
                                    @endphp
                                @endif
                            @endforeach

                            <tr class="fw-bold">
                                <td colspan="6" class="text-center">Total</td>
                                <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
@endsection
