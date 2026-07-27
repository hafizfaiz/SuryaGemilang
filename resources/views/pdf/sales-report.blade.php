<!DOCTYPE html>
<html>

<head>
    <title>Sales Report</title>
</head>

<body>

<h1>
    SURYA GEMILANG
</h1>

<h3>
    Laporan Penjualan
</h3>

<table border="1" width="100%" cellpadding="5" style="border-collapse: collapse;">

<tr>
    <th style="width: 5%;">No</th>
    <th style="width: 20%;">Invoice</th>
    <th style="width: 25%;">Customer</th>
    <th style="width: 35%;">Daftar Barang (Qty &times; Harga = Subtotal)</th>
    <th style="width: 15%;">Total Harga</th>
</tr>

@foreach($sales as $item)
@php
    $products = is_string($item->products) ? json_decode($item->products, true) : $item->products;
@endphp
<tr>
    <td style="text-align: center; vertical-align: top;">{{ $loop->iteration }}</td>
    <td style="vertical-align: top;">{{ $item->invoice_number }}</td>
    <td style="vertical-align: top;">{{ $item->customer_name }}</td>
    <td style="vertical-align: top;">
        @if(is_array($products) && count($products) > 0)
            <ul style="margin: 0; padding-left: 16px; list-style-type: disc;">
            @foreach($products as $prod)
                @php
                    $productObj = \App\Models\Product::find($prod['product_id'] ?? 0);
                    $productName = $productObj ? $productObj->product_name : ('Produk ID ' . ($prod['product_id'] ?? '-'));
                    $qty = (int) ($prod['quantity'] ?? 0);
                    $price = (float) ($prod['price'] ?? ($productObj ? $productObj->price : 0));
                    $subtotal = $qty * $price;
                @endphp
                <li style="margin-bottom: 4px;">
                    <strong>{{ $productName }}</strong><br>
                    <span style="font-size: 11px; color: #555;">Qty: {{ $qty }} &times; Rp {{ number_format($price, 0, ',', '.') }} = <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></span>
                </li>
            @endforeach
            </ul>
        @else
            -
        @endif
    </td>
    <td style="text-align: right; vertical-align: top;">
        <strong>Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
    </td>
</tr>

@endforeach

</table>

</body>

</html>