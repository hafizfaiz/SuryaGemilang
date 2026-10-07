<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class SalesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Sales by Category';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '250px';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $products = Product::query()->with('category')->get(['id', 'category_id'])->keyBy('id');
        $allSales = Sales::query()->get(['products']);

        $categorySales = [];

        foreach ($allSales as $sale) {
            foreach ($sale->products ?? [] as $item) {
                $pid = (int) ($item['product_id'] ?? 0);
                $subtotal = (float) ($item['subtotal'] ?? (((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0))));

                $product = $products->get($pid);
                $catName = ($product && $product->category) ? $product->category->category_name : 'Lainnya';

                $categorySales[$catName] = ($categorySales[$catName] ?? 0) + $subtotal;
            }
        }

        arsort($categorySales);

        // Fallback if no sales data yet
        if (empty($categorySales)) {
            $categories = Category::query()->pluck('category_name')->toArray();
            foreach ($categories as $cat) {
                $categorySales[$cat] = 0;
            }
        }

        $colors = [
            '#3b82f6', // blue
            '#10b981', // emerald
            '#f59e0b', // amber
            '#8b5cf6', // purple
            '#ec4899', // pink
            '#06b6d4', // cyan
            '#f97316', // orange
            '#14b8a6', // teal
            '#6366f1', // indigo
            '#84cc16', // lime
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => array_values($categorySales),
                    'backgroundColor' => array_slice(array_merge($colors, $colors), 0, count($categorySales)),
                    'borderColor' => array_slice(array_merge($colors, $colors), 0, count($categorySales)),
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => array_keys($categorySales),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
