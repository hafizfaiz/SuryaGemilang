<?php

namespace App\Filament\Widgets;

use App\Models\Sales;
use App\Models\CustomerReturn;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatistics extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Total transaksi
        $totalSales = Sales::count();

        $lastMonthSales = Sales::query()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $transactionGrowth = $this->calculateGrowth($totalSales, $lastMonthSales);

        // Penjualan hari ini
        $todaySales = Sales::query()
            ->whereDate('created_at', today())
            ->sum('total_price');

        $yesterdaySales = Sales::query()
            ->whereDate('created_at', today()->subDay())
            ->sum('total_price');

        $dailyGrowth = $this->calculateGrowth($todaySales, $yesterdaySales);

        // Penjualan bulan ini
        $monthSales = Sales::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $lastMonthRevenue = Sales::query()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');

        $monthlyGrowth = $this->calculateGrowth($monthSales, $lastMonthRevenue);

        // Retur Bulan Ini
        $monthReturns = CustomerReturn::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthReturns = CustomerReturn::query()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $returnGrowth = $this->calculateGrowth($monthReturns, $lastMonthReturns);

        // Data mini chart riil untuk 7 hari terakhir
        $dailySalesCounts = [];
        $dailySalesAmounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $dailySalesCounts[] = Sales::query()->whereDate('created_at', $date)->count();
            $dailySalesAmounts[] = (float) Sales::query()->whereDate('created_at', $date)->sum('total_price');
        }

        // Data mini chart riil untuk 6 bulan terakhir
        $monthlySalesAmounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlySalesAmounts[] = (float) Sales::query()
                ->whereMonth('created_at', $monthDate->month)
                ->whereYear('created_at', $monthDate->year)
                ->sum('total_price');
        }

        return [
            Stat::make('Total Transaksi', number_format($totalSales))
                ->description($this->growthText($transactionGrowth))
                ->descriptionIcon($transactionGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transactionGrowth >= 0 ? 'success' : 'danger')
                ->chart(empty(array_filter($dailySalesCounts)) ? [0, 0, 0, 0, 0, 0, 0] : $dailySalesCounts),

            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($todaySales, 0, ',', '.'))
                ->description($this->growthText($dailyGrowth))
                ->descriptionIcon($dailyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($dailyGrowth >= 0 ? 'success' : 'danger')
                ->chart(empty(array_filter($dailySalesAmounts)) ? [0, 0, 0, 0, 0, 0, 0] : $dailySalesAmounts),

            Stat::make('Penjualan Bulan Ini', 'Rp ' . number_format($monthSales, 0, ',', '.'))
                ->description($this->growthText($monthlyGrowth))
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger')
                ->chart(empty(array_filter($monthlySalesAmounts)) ? [0, 0, 0, 0, 0, 0] : $monthlySalesAmounts),

            Stat::make('Retur Bulan Ini', number_format($monthReturns) . ' Barang')
                ->description($this->growthText($returnGrowth))
                ->descriptionIcon($returnGrowth <= 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
                ->color($returnGrowth <= 0 ? 'success' : 'warning')
                ->icon('heroicon-m-arrow-path'),
        ];
    }

    private function calculateGrowth($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    private function growthText($growth): string
    {
        return number_format(abs($growth), 1) . '% dibanding periode sebelumnya';
    }
}