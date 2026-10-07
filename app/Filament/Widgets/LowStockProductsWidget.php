<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\StockIns\StockInResource;

class LowStockProductsWidget extends BaseWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Produk Wajib Restok')
            ->emptyStateHeading('Semua produk dalam batas aman')
            ->emptyStateDescription('Tidak ada produk yang stoknya menyentuh atau di bawah Reorder Point saat ini.')
            ->emptyStateIcon('heroicon-m-check-circle')
            ->query(
                Product::query()
                    ->with('category')
                    ->whereColumn('stock', '<=', 'reorder_point')
                    ->orderBy('stock', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok Saat Ini')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state, $record) => $state <= ($record->safety_stock ?? 0) ? 'danger' : 'warning'),

                Tables\Columns\TextColumn::make('reorder_point')
                    ->label('ROP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('safety_stock')
                    ->label('Safety Stock')
                    ->sortable(),

                Tables\Columns\TextColumn::make('daily_usage')
                    ->label('Pemakaian / Hari')
                    ->numeric(2)
                    ->suffix(' unit'),

                Tables\Columns\TextColumn::make('estimasi_habis')
                    ->label('Estimasi Habis')
                    ->state(function (Product $record): string {
                        if ($record->daily_usage <= 0) {
                            return 'N/A';
                        }
                        $days = round($record->stock / $record->daily_usage, 1);
                        return $days <= 0 ? 'Habis' : $days . ' hari';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Habis' || floatval($state) <= 2 ? 'danger' : 'warning'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Product $record): string => ProductResource::getUrl('view', ['record' => $record])),

                Action::make('restock')
                    ->label('Buat Stock In')
                    ->icon('heroicon-m-plus-circle')
                    ->color('primary')
                    ->url(fn () => StockInResource::getUrl('create')),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
