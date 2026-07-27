<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Keluar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #eeeeee; text-align: center; }
        th, td { padding: 8px; text-align: left; }
        .number { text-align: right; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h2>SURYA GEMILANG</h2>
    <p>Laporan Barang Keluar</p>
    <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Tanggal</th>
            <th>Invoice No</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Nama Barang</th>
            <th width="10%">Jumlah</th>
            <th>Alasan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockOuts as $item)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td style="text-align:center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $item->sale->invoice_number ?? '-' }}</td>
            <td>{{ $item->product->category->category_name ?? '-' }}</td>
            <td>{{ $item->product->type_name ?? '-' }}</td>
            <td>{{ $item->product->product_name ?? '-' }}</td>
            <td style="text-align:center">{{ $item->out_quantity }}</td>
            <td>{{ $item->out_reason }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">
    Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}
</div>
</body>
</html>
