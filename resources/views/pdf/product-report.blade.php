<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stock Produk</title>
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
    <p>Laporan Daftar Barang / Produk</p>
    <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Stok Aman</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->category->category_name ?? '-' }}</td>
            <td>{{ $product->type_name ?? '-' }}</td>
            <td class="number">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td style="text-align:center">{{ $product->stock }}</td>
            <td style="text-align:center">{{ $product->safety_stock }}</td>
            <td style="text-align:center">
                @if($product->stock <= $product->safety_stock)
                    Stok Rendah
                @else
                    Aman
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">
    Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}
</div>
</body>
</html>
