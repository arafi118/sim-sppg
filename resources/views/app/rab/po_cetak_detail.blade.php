<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 400px;
            /* lebar kwitansi */
            margin: 30px auto 0 auto;
            /* margin atas 30px, horizontal auto */
        }

        .struk-box {
            border: 1px solid black;
            padding: 8px;
            /* bisa juga tambahkan margin-top di sini jika ingin tambahan jarak */
        }

        .header {
            margin-bottom: 20px;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table td {
            border: 0px solid black;
            padding: 4px 6px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .signature {
            margin-top: 10px;
            text-align: right;
        }
    </style>

</head>

<body>
    <div class="struk-box">
        <div class="header text-center" style="font-size: 14px; margin-bottom: 15px;">
            <p>NO PO: {{ str_pad($po->id, 2, '0', STR_PAD_LEFT) }} /
                {{ strtoupper($po->status_bayar) }}</p>
            <p style="text-transform: uppercase;">
                Tanggal {{ \Carbon\Carbon::parse($po->tanggal)->isoFormat('D MMMM Y') }}
            </p>
        </div>

        <table style="margin-top: 39px;">
            <thead>
                <tr>
                    {{-- <td class="text-center" style="width: 10%;"><b>No</b></td> --}}
                    <td class="text-left" style="width: 30%;"><b>Bahan Pangan</b></td>
                    <td class="text-center" style="width: 10%;"><b>Kebutuhan</b></td>
                    <td class="text-right" style="width: 70%;"><b>Jumlah</b></td>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp
                @foreach ($po->poDetail as $detail)
                    <tr>
                        {{-- <td class="text-center">{{ $no }}</td> --}}
                        <td class="text-left">{{ $detail->bahanPangan->nama ?? '-' }}</td>
                        <td class="text-left">{{ number_format($detail->jumlah, 2, ',', '.') }} (Kg)</td>
                        <td class="text-right">{{ number_format($detail->total_harga, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $no++;
                        $grandTotal += $detail->total_harga;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><b> Total : &nbsp;
                            Rp.{{ number_format($grandTotal, 2, ',', '.') }}</b></td>
                </tr>
            </tbody>
        </table>

        {{-- <div class="signature">
            <p>________________</p>
            <p>Tanda Tangan</p>
        </div> --}}
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
