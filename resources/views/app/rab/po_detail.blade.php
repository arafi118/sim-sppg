@extends('app.layouts.app')

@section('content')
    <title>{{ $title }}</title>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail PO</h4>

            @foreach ($referensiPOs as $refPo)
                @if ($refPo->poDetail->count() > 0)
                    <p>
                        Tanggal {{ \Carbon\Carbon::parse($refPo->tanggal)->locale('id')->isoFormat('D MMMM Y') }}

                    </p>

                    <table class="table table-bordered mt-1 mb-3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Pangan</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Kebutuhan</th>
                                <th>Jumlah Input</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $totalHarga = 0;
                            @endphp
                            @foreach ($refPo->poDetail as $detail)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $detail->bahanPangan->nama ?? '-' }}</td>
                                    <td align="center">{{ $detail->bahanPangan->satuan ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td align="center">{{ number_format($detail->jumlah, 2, ',', '.') }} (Kg)</td>
                                    <td align="center">
                                        {{ $detail->jumlah_input % 1 == 0
                                            ? number_format($detail->jumlah_input, 0, ',', '.')
                                            : number_format($detail->jumlah_input, 2, ',', '.') }}
                                        (Kg)
                                    </td>
                                    <td class="text-end">{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    $no++;
                                    $totalHarga += $detail->total_harga;
                                @endphp
                            @endforeach
                            <tr class="fw-bold">
                                <td colspan="6" class="text-center">Total</td>
                                <td class="text-end">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            @endforeach
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ url('app/rab/po/cetak/' . $po->id) }}" class="btn btn-danger me-3">
                    <i class="icon-base bx bx-printer me-1"></i>
                    <span class="align-middle">Cetak</span>
                </a>


                <a href="{{ url('/app/rab') }}" class="btn btn-primary">Kembali</a>
            </div>

        </div>
    </div>
@endsection
