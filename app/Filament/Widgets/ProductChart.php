<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class ProductChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Produk Terlaris';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '250px';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $allProducts = Product::query()->get(['id', 'product_name'])->keyBy('id');
        $allSales = Sales::query()->get(['products']);

        $productSales = [];
        foreach ($allSales as $sale) {
            foreach ($sale->products ?? [] as $item) {
                $pid = (int) ($item['product_id'] ?? 0);
                $qty = (int) ($item['quantity'] ?? 0);
                if ($pid > 0 && $qty > 0) {
                    $productSales[$pid] = ($productSales[$pid] ?? 0) + $qty;
                }
            }
        }

        arsort($productSales);
        $top5Sales = array_slice($productSales, 0, 5, true);

        $labels = [];
        $data = [];

        foreach ($top5Sales as $pid => $soldQty) {
            $product = $allProducts->get($pid);
            $name = $product ? $product->product_name : "Produk #{$pid}";
            $shortName = mb_strlen($name) > 25 ? mb_substr($name, 0, 23) . '...' : $name;
            $labels[] = $shortName;
            $data[] = $soldQty;
        }

        // Fallback if no sales yet
        if (empty($data)) {
            $sampleProducts = $allProducts->take(5);
            foreach ($sampleProducts as $p) {
                $labels[] = mb_strlen($p->product_name) > 25 ? mb_substr($p->product_name, 0, 23) . '...' : $p->product_name;
                $data[] = 0;
            }
        }

        $colors = [
            '#3b82f6', // blue
            '#10b981', // emerald
            '#f59e0b', // amber
            '#8b5cf6', // purple
            '#f43f5e', // rose
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Terjual (Unit)',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}