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
    <p>Tanggal Cetak: <?php echo e(date('d-m-Y')); ?></p>
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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="text-align:center"><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($product->product_name); ?></td>
            <td><?php echo e($product->category->category_name ?? '-'); ?></td>
            <td><?php echo e($product->type_name ?? '-'); ?></td>
            <td class="number">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
            <td style="text-align:center"><?php echo e($product->stock); ?></td>
            <td style="text-align:center"><?php echo e($product->safety_stock); ?></td>
            <td style="text-align:center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock <= $product->safety_stock): ?>
                    Stok Rendah
                <?php else: ?>
                    Aman
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>
<div class="footer">
    Dicetak oleh: <?php echo e(auth()->user()->name ?? 'Admin'); ?>

</div>
</body>
</html>
<?php /**PATH C:\laragon\www\SuryaGemilang\resources\views/pdf/product-report.blade.php ENDPATH**/ ?>