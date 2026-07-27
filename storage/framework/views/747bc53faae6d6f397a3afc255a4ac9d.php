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
    <p>Tanggal Cetak: <?php echo e(date('d-m-Y')); ?></p>
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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $stockOuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="text-align:center"><?php echo e($loop->iteration); ?></td>
            <td style="text-align:center"><?php echo e($item->created_at->format('d-m-Y H:i')); ?></td>
            <td><?php echo e($item->sale->invoice_number ?? '-'); ?></td>
            <td><?php echo e($item->product->category->category_name ?? '-'); ?></td>
            <td><?php echo e($item->product->type_name ?? '-'); ?></td>
            <td><?php echo e($item->product->product_name ?? '-'); ?></td>
            <td style="text-align:center"><?php echo e($item->out_quantity); ?></td>
            <td><?php echo e($item->out_reason); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>
<div class="footer">
    Dicetak oleh: <?php echo e(auth()->user()->name ?? 'Admin'); ?>

</div>
</body>
</html>
<?php /**PATH C:\laragon\www\SuryaGemilang\resources\views/pdf/stock-out-report.blade.php ENDPATH**/ ?>