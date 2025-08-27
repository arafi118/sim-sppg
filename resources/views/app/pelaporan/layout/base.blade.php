<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dokumen' }}</title>

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

        .break {
            page-break-after: always;
        }

        ol.romawi,
        ol.angka,
        ol.huruf-kecil,
        ol.huruf-besar {
            margin: 0;
            padding-left: 20px;
        }

        ol.romawi {
            list-style-type: upper-roman;
            padding-top: 5px;
        }

        ol.angka {
            list-style-type: decimal;
            padding-top: 5px;
        }

        ol.huruf-kecil {
            list-style-type: lower-alpha;
            padding-top: 5px;
        }

        ol.huruf-besar {
            list-style-type: upper-alpha;
            padding-top: 5px;
        }

        .border-table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .border-table th,
        .border-table td {
            border: 1px solid black;
            padding: 2px 5px;
            text-align: left;
        }

        .border-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>
