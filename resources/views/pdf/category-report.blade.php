<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kategori & Merek Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #eeeeee; text-align: center; }
        th, td { padding: 8px; text-align: left; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h2>SURYA GEMILANG</h2>
    <p>Laporan Kategori & Merek Barang</p>
    <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th width="10%">No</th>
            <th>Kategori Barang</th>
            <th>Merek / Tipe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $category->category_name }}</td>
            <td>{{ $category->type_name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">
    Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}
</div>
</body>
</html>
