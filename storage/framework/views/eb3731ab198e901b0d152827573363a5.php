<!DOCTYPE html>
<html>

<head>
    <title>Struk Pembelian - <?php echo e($sale->invoice_number); ?></title>
    <style>
        @page {
            margin: 5mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            width: 80mm;
            margin: 0 auto;
        }

        .receipt {
            padding: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .divider-double {
            border: none;
            border-top: 2px double #000;
            margin: 6px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            line-height: 1.6;
        }

        .info-row .label {
            color: #555;
        }

        .info-row .value {
            font-weight: bold;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }

        .items-table th {
            font-size: 10px;
            text-align: left;
            padding: 2px 0;
            border-bottom: 1px solid #000;
            font-weight: bold;
        }

        .items-table th:last-child {
            text-align: right;
        }

        .item-name {
            font-size: 11px;
            font-weight: bold;
            padding-top: 4px;
        }

        .item-detail {
            font-size: 10px;
            color: #555;
            padding-bottom: 3px;
        }

        .item-subtotal {
            font-size: 11px;
            text-align: right;
            vertical-align: bottom;
            padding-bottom: 3px;
        }

        .total-section {
            margin-top: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            line-height: 1.6;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 2px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .footer .thanks {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="receipt">
        
        <div class="header">
            <h1>SURYA GEMILANG</h1>
            <p>Toko Bahan Bangunan</p>
        </div>

        <hr class="divider-double">

        
        <table style="width: 100%; font-size: 11px;">
            <tr>
                <td>No. Invoice</td>
                <td style="text-align: right; font-weight: bold;"><?php echo e($sale->invoice_number); ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td style="text-align: right;"><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td style="text-align: right;"><?php echo e($sale->customer_name ?: '-'); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->user): ?>
            <tr>
                <td>Kasir</td>
                <td style="text-align: right;"><?php echo e($sale->user->name); ?></td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </table>

        <hr class="divider">

        
        <?php
            $products = is_string($sale->products) ? json_decode($sale->products, true) : $sale->products;
        ?>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($products) && count($products) > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $productObj = \App\Models\Product::find($prod['product_id'] ?? 0);
                            $productName = $productObj ? $productObj->product_name : ('Produk ID ' . ($prod['product_id'] ?? '-'));
                            $qty = (int) ($prod['quantity'] ?? 0);
                            $price = (float) ($prod['price'] ?? ($productObj ? $productObj->price : 0));
                            $subtotal = $qty * $price;
                        ?>
                        <tr>
                            <td>
                                <div class="item-name"><?php echo e($productName); ?></div>
                                <div class="item-detail"><?php echo e($qty); ?> x Rp <?php echo e(number_format($price, 0, ',', '.')); ?></div>
                            </td>
                            <td class="item-subtotal">
                                Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align: center; padding: 8px 0;">Tidak ada item</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <hr class="divider-double">

        
        <div class="total-section">
            <?php
                $totalItems = 0;
                if (is_array($products)) {
                    foreach ($products as $prod) {
                        $totalItems += (int) ($prod['quantity'] ?? 0);
                    }
                }
            ?>
            <table style="width: 100%;">
                <tr style="font-size: 11px;">
                    <td>Total Item</td>
                    <td style="text-align: right;"><?php echo e($totalItems); ?> pcs</td>
                </tr>
                <tr style="font-size: 14px; font-weight: bold;">
                    <td>TOTAL</td>
                    <td style="text-align: right;">Rp <?php echo e(number_format($sale->total_price, 0, ',', '.')); ?></td>
                </tr>
            </table>
        </div>

        <hr class="divider">

        
        <div class="footer">
            <p class="thanks">Terima Kasih!</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
            <p style="margin-top: 6px;"><?php echo e($sale->created_at->format('d/m/Y H:i:s')); ?></p>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\SuryaGemilang\resources\views/pdf/sales-receipt.blade.php ENDPATH**/ ?>