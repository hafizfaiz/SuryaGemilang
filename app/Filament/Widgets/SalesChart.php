<?php

namespace App\Filament\Widgets;

use App\Models\Sales;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Pendapatan Penjualan';

    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '10s';


    protected function getData(): array
    {

        $sales = Sales::query()
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');


        $data = [];

        for ($i = 1; $i <= 12; $i++) {

            $data[] = $sales[$i] ?? 0;

        }


        return [

            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#86efac',
                ],

            ],


            'labels' => [

                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des',

            ],

        ];

    }


    protected function getType(): string
    {
        return 'line';
    }
}