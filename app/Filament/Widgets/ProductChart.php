<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class ProductChart extends ChartWidget
{
    protected ?string $heading = '5 Produk Terlaris';

    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $allProducts = Product::query()->get(['id', 'product_name', 'stock', 'daily_usage']);
        $salesItems = Sales::query()
            ->get(['products'])
            ->flatMap(fn (Sales $sale) => $sale->products ?? []);

        $productSales = [];
        foreach ($salesItems as $item) {
            $pid = (int) ($item['product_id'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            if ($pid > 0 && $qty > 0) {
                $productSales[$pid] = ($productSales[$pid] ?? 0) + $qty;
            }
        }

        $topProducts = $allProducts->map(function ($product) use ($productSales) {
            $product->sold_qty = $productSales[$product->id] ?? 0;
            return $product;
        })->sortByDesc(fn ($product) => ($product->sold_qty * 10000) + $product->stock)->take(5)->values();

        return [
            'datasets' => [
                [
                    'label' => 'Terjual (Unit)',
                    'data' => $topProducts->pluck('sold_qty')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $topProducts->pluck('product_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}