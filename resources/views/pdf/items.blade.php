<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris</title>

    <style>

        *{
            font-family: DejaVu Sans,sans-serif;
        }

        body{
            font-size:11px;
            color:#111;
        }

        .header{
            border-bottom:2px solid #2563eb;
            margin-bottom:15px;
            padding-bottom:10px;
        }

        .header h1{
            margin:0;
            color:#1e3a8a;
            font-size:18px;
        }

        .header p{
            margin:3px 0;
            font-size:10px;
        }

        h2{
            margin-top:25px;
            margin-bottom:8px;
            color:#1e3a8a;
            border-bottom:1px solid #2563eb;
            padding-bottom:4px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-bottom:20px;
        }

        th,td{
            border:1px solid #d1d5db;
            padding:5px;
            font-size:10px;
            vertical-align:top;
        }

        th{
            background:#eff6ff;
            color:#1e3a8a;
        }

        tr:nth-child(even){
            background:#fafafa;
        }

        .footer{
            margin-top:15px;
            text-align:right;
            font-size:9px;
            color:#777;
        }

    </style>

</head>
<body>

<div class="header">

    <h1>Laporan Inventaris Laboratorium TKJ</h1>

    <p>
        Dicetak :
        {{ $tanggal }}
    </p>

</div>

@foreach($labs as $labName => $items)

<h2>{{ strtoupper($labName) }}</h2>

<table>

    <thead>

    <tr>

        <th width="5%">No</th>

        <th width="22%">Nama Barang</th>

        <th width="18%">Kategori</th>

        <th width="25%">Lokasi</th>

        <th width="8%">Jumlah</th>

        <th width="12%">Status</th>

        <th>Keterangan</th>

    </tr>

    </thead>

    <tbody>

    @php
        $no=1;
    @endphp

    @foreach($items as $item)

    <tr>

        <td>{{ $no++ }}</td>

        <td>{{ $item->nama }}</td>

        <td>{{ $item->kategori }}</td>

        <td>{{ $item->lokasi }}</td>

        <td style="text-align:center">
            {{ $item->jumlah }}
        </td>

        <td>
            {{ $item->status->label() }}
        </td>

        <td>
            {{ $item->keterangan }}
        </td>

    </tr>

    @endforeach

    </tbody>

</table>

@endforeach

<div class="footer">

Sistem Inventaris Laboratorium TKJ

</div>

</body>
</html>
