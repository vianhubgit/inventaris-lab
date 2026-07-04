<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Rusak & Hilang</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 11px; color: #111; margin: 0; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { margin: 0; font-size: 18px; color: #1e3a8a; }
        .header p { margin: 2px 0 0; color: #555; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; }
        th { background: #eff6ff; color: #1e3a8a; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) td { background: #f9fafb; }
        .footer { margin-top: 12px; font-size: 9px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Barang Rusak &amp; Hilang</h1>
        <p>Dicetak pada: {{ $tanggal }} &mdash; Total laporan: {{ $reports->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>Jenis</th><th>Barang</th>
                <th>Lokasi</th><th>Jumlah</th><th>Status</th><th>Pelapor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->reported_at?->format('d-m-Y H:i') }}</td>
                    <td>{{ $r->type->label() }}</td>
                    <td>{{ $r->item?->nama }}</td>
                    <td>{{ collect([$r->lab?->nama, $r->group?->display_name, $r->labTable?->display_name])->filter()->implode(' / ') }}</td>
                    <td>{{ $r->jumlah }}</td>
                    <td>{{ $r->status->label() }}</td>
                    <td>{{ $r->user?->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Sistem Inventaris Lab TKJ &bull; Lab A, Lab B, TEFA</div>
</body>
</html>
