<?php

namespace App\Filament\Widgets;

use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Revenue';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '250px';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $year = now()->year;

        $monthlySales = Sales::query()
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = (float) ($monthlySales[$i] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue (Rp)',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}