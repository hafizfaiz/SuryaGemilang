<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStockOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $lowStockCount = Product::query()
            ->whereColumn('stock', '<=', 'reorder_point')
            ->count();

        return [
            Stat::make('Total Produk', number_format(Product::count()))
                ->description('Jumlah seluruh produk')
                ->descriptionIcon('heroicon-m-cube')
                ->icon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Kategori', number_format(Category::count()))
                ->description('Kategori tersedia')
                ->descriptionIcon('heroicon-m-tag')
                ->icon('heroicon-m-squares-2x2')
                ->color('success'),

            Stat::make('Total Stock', number_format(Product::sum('stock')))
                ->description('Jumlah stok gudang')
                ->descriptionIcon('heroicon-m-archive-box')
                ->icon('heroicon-m-cube-transparent')
                ->color('info'),

            Stat::make('Butuh Restock', number_format($lowStockCount) . ' Produk')
                ->description($lowStockCount > 0 ? 'Stok < Stok Aman' : 'Semua stok dalam batas aman')
                ->descriptionIcon($lowStockCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->icon('heroicon-m-exclamation-circle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),
        ];
    }
}