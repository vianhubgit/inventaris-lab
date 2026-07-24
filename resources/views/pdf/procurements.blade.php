<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">

<title>Laporan Peminjaman Barang</title>

<style>
* {
    font-family: DejaVu Sans, sans-serif;
}

body {
    font-size: 11px;
    color: #111;
}

.header {
    border-bottom: 2px solid #2563eb;
    padding-bottom: 8px;
    margin-bottom: 12px;
}

.header h1 {
    margin:0;
    font-size:18px;
    color:#1e3a8a;
}

table {
    width:100%;
    border-collapse:collapse;
}

th, td {
    border:1px solid #d1d5db;
    padding:5px;
    vertical-align:top;
}

th {
    background:#eff6ff;
    color:#1e3a8a;
    text-align:center;
}

.center {
    text-align:center;
}

.footer {
    margin-top:12px;
    font-size:9px;
    text-align:right;
}
</style>

</head>

<body>

<div class="header">

<h1>Laporan Peminjaman Barang</h1>

<p>
Dicetak pada: {{ $tanggal }}
<br>
Total peminjaman: {{ $groups->count() }}
</p>

</div>


<table>

<thead>
<tr>
    <th width="5%">No</th>
    <th width="15%">Tanggal</th>
    <th width="18%">Peminjam</th>
    <th>Barang</th>
    <th width="10%">Jumlah</th>
    <th width="15%">Status</th>
    <th width="25%">Catatan Admin</th>
</tr>
</thead>


<tbody>

@foreach($groups as $index => $group)

@php
    $jumlahBaris = count($group['items']);
@endphp


@foreach($group['items'] as $key => $item)

<tr>

@if($key === 0)

<td rowspan="{{ $jumlahBaris }}" class="center">
    {{ $index + 1 }}
</td>

<td rowspan="{{ $jumlahBaris }}">
    {{ $group['requested_at']->format('d-m-Y H:i') }}
</td>

<td rowspan="{{ $jumlahBaris }}">
    {{ $group['user']?->name }}
</td>

@endif


<td>
    {{ $item->nama_barang }}
</td>


<td class="center">
    {{ $item->jumlah }}
</td>


<td>
    {{ $item->status->label() }}
</td>

<td>
    {{ $item->catatan_admin ?? '-' }}
</td>

</tr>

@endforeach

@endforeach

</tbody>

</table>


<div class="footer">
Sistem Inventaris Lab TKJ • Lab A, Lab B, TEFA
</div>


</body>
</html>
