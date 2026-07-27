<?php

namespace App\Filament\Widgets;

use App\Models\Sales;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\ViewAction;
use App\Filament\Resources\Sales\SalesResource;

class LatestSales extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Penjualan Terbaru')
            ->emptyStateHeading('Tidak ada data penjualan')
            ->query(
                Sales::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->state(fn (Sales $record): string => count($record->products ?? []) . ' jenis barang')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Sales $record): string => SalesResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated(false);
    }
}