<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Tanda Terima</title>
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
            margin-bottom: 5px;
        }

        .nomor {
            text-align: center;
            font-size: 12px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .kotak-uang {
            border: 1px solid #000;
            display: inline-block;
            padding: 6px 15px;
            font-weight: bold;
            font-style: italic;
            text-align: center;
            min-width: 100px;
            /* lebar minimum */
        }


        .materai {
            border: 1px solid #000;
            display: inline-block;
            padding: 20px 10px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="judul">BUKTI TANDA TERIMA</div>
    <div class="nomor">Nomor : 01/BTT/V/2025</div>

    <table style="margin-bottom:10px;">
        <tr>
            <td style="width:20%;">SUDAH TERIMA DARI</td>
            <td style="width:4%; text-align:right;">:</td>
            <td>Yayasan Perjuangan Untuk Kesejahteraan Rakyat/Badan Gizi Nasional</td>
        </tr>

        <tr>
            <td>UANG SEBESAR</td>
            <td style="width:4%; text-align:right;">:</td>
            <td>
                <div class="kotak-uang"> Rp. 89.991.000</div>
            </td>
        </tr>
    </table>

    <table style="margin-bottom:15px;">
        <tr>
            <td style="width:20%;">UNTUK KEPERLUAN</td>
            <td style="width:4%; text-align:right;">:</td>
            <td>Operasional Satuan Pelayanan Pemenuhan Gizi Kota Depok Tapos 1</td>
        </tr>
        <tr>
            <td>DANA SISA OPERASIONAL PERIODE SEBELUMNYA</td>
            <td style="width:4%; text-align:right;">:</td>
            <td>Rp -</td>
        </tr>
    </table>

    <br><br>

    <table style="margin-top:20px; font-size:12px;">
        <tr>
            <!-- Kolom kiri -->
            <td width="40%" align="center" valign="top">
                Yang Menyerahkan <br>
                Yayasan Perjuangan Untuk <br>
                Kesejahteraan Rakyat <br><br><br><br>
                ...................................... <br>
                Ketua/Mewakili
            </td>

            <!-- Kolom tengah (Materai) -->
            <td width="20%" align="center" valign="top">
                {{-- <div class="materai">Materai<br>10.000</div>
            </td> --}}

                <!-- Kolom kanan -->
            <td width="40%" align="center" valign="top">
                Depok, 23 Maret 2025 <br>
                Yang Menerima <br>
                Staf Akuntansi SPPG Kota Depok Tapos 1 <br><br><br><br>
                <u>Bobby Mandala Putra, S.Ak</u>
            </td>
        </tr>

        <!-- Mengetahui -->
        <tr>
            <td colspan="3" align="center" style="padding-top:50px;">
                Mengetahui, <br>
                Kepala SPPG Khusus Kota Depok Tapos 1 <br><br><br><br>
                <u>Rakha Pratama, S.Pd., S.Ag., M.Han</u>
            </td>
        </tr>
    </table>

</body>

</html>
