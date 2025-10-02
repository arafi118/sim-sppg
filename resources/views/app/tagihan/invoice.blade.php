@php
    use App\Utils\Tanggal;
@endphp

<style>
    * {
        font-family: 'Arial', sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }

    .judul {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
    }

    .subjudul {
        text-align: center;
        margin-bottom: 30px;
    }

    .rata-kanan {
        text-align: right;
    }

    .rata-kiri {
        text-align: left;
    }

    .rata-tengah {
        text-align: center;
    }

    .ratakanankiri {
        text-align: justify;
        text-justify: inter-word;
    }

    .paragraf {
        text-align: justify;
        text-justify: inter-word;
        text-indent: 2em;
    }

    .border-table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    table:not(.border-table) tr td,
    table:not(.border-table) tr th {
        vertical-align: top;
    }

    .border-table th,
    .border-table td {
        border: 1px solid black;
        padding: 2px 5px;
    }

    .border-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .page-break {
        page-break-before: always;
        break-before: page;
    }
</style>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="3">
            <b>INVOICE</b>
        </td>
    </tr>
    <tr>
        <td width="20%">Nomor</td>
        <td width="2%">:</td>
        <td>{{ $invoice->no_invoice }}</td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>{{ Tanggal::tglLatin($invoice->tanggal_invoice) }}</td>
    </tr>
    <tr>
        <td>Customer</td>
        <td>:</td>
        <td>{{ $profil->nama }}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $profil->alamat }}</td>
    </tr>
</table>

<table class="border-table" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">No</td>
        <td align="center">Tanggal Kirim</td>
        <td align="center">Nama Barang</td>
        <td align="center">Jumlah</td>
        <td align="center">Satuan</td>
        <td align="center">Harga</td>
        <td align="center">Total</td>
    </tr>

    @php
        $grandTotal = 0;
    @endphp
    @foreach ($invoice->tagihan as $tagihan)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">{{ $invoice->tanggal_invoice }}</td>
            <td>{{ $tagihan->bahanPangan->nama }}</td>
            <td align="right">{{ $tagihan->kebutuhan }}</td>
            <td align="center">{{ $tagihan->bahanPangan->satuan }}</td>
            <td align="right">{{ number_format($tagihan->harga) }}</td>
            <td align="right">{{ number_format($tagihan->total) }}</td>
        </tr>

        @php
            $grandTotal += $tagihan->total;
        @endphp
    @endforeach

    <tr>
        <td colspan="6" height="30" align="center">Total</td>
        <td align="right">{{ number_format($grandTotal) }}</td>
    </tr>
</table>
