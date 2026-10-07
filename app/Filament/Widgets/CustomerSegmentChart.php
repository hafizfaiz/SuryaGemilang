<?php

namespace App\Filament\Widgets;

use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class CustomerSegmentChart extends ChartWidget
{
    protected ?string $heading = 'Customer Segment';

    protected static ?int $sort = 6;

    protected ?string $maxHeight = '250px';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $segmentData = Sales::query()
            ->selectRaw("COALESCE(NULLIF(customer_segment, ''), 'Retail') as segment_name, SUM(total_price) as revenue")
            ->groupBy('segment_name')
            ->orderByDesc('revenue')
            ->pluck('revenue', 'segment_name')
            ->toArray();

        if (empty($segmentData)) {
            $segmentData = [
                'Retail' => 0,
                'Grosir' => 0,
                'Bengkel' => 0,
                'Corporate' => 0,
            ];
        }

        $colorMap = [
            'Corporate' => '#8b5cf6', // purple
            'Grosir'    => '#f59e0b', // amber
            'Bengkel'   => '#06b6d4', // cyan
            'Retail'    => '#10b981', // emerald
        ];

        $backgroundColors = [];
        foreach (array_keys($segmentData) as $seg) {
            $backgroundColors[] = $colorMap[$seg] ?? '#3b82f6';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => array_map('floatval', array_values($segmentData)),
                    'backgroundColor' => $backgroundColors,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => array_keys($segmentData),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
