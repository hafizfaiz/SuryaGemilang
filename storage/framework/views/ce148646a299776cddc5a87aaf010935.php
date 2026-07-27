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

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $products = is_string($item->products) ? json_decode($item->products, true) : $item->products;
?>
<tr>
    <td style="text-align: center; vertical-align: top;"><?php echo e($loop->iteration); ?></td>
    <td style="vertical-align: top;"><?php echo e($item->invoice_number); ?></td>
    <td style="vertical-align: top;"><?php echo e($item->customer_name); ?></td>
    <td style="vertical-align: top;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($products) && count($products) > 0): ?>
            <ul style="margin: 0; padding-left: 16px; list-style-type: disc;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $productObj = \App\Models\Product::find($prod['product_id'] ?? 0);
                    $productName = $productObj ? $productObj->product_name : ('Produk ID ' . ($prod['product_id'] ?? '-'));
                    $qty = (int) ($prod['quantity'] ?? 0);
                    $price = (float) ($prod['price'] ?? ($productObj ? $productObj->price : 0));
                    $subtotal = $qty * $price;
                ?>
                <li style="margin-bottom: 4px;">
                    <strong><?php echo e($productName); ?></strong><br>
                    <span style="font-size: 11px; color: #555;">Qty: <?php echo e($qty); ?> &times; Rp <?php echo e(number_format($price, 0, ',', '.')); ?> = <strong>Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></strong></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        <?php else: ?>
            -
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td style="text-align: right; vertical-align: top;">
        <strong>Rp <?php echo e(number_format($item->total_price, 0, ',', '.')); ?></strong>
    </td>
</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</table>

</body>

</html><?php /**PATH C:\laragon\www\SuryaGemilang\resources\views/pdf/sales-report.blade.php ENDPATH**/ ?>