<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.category_name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type_name')
                    ->label('Merek')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product_name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->numeric()
                    ->searchable(),
                TextColumn::make('safety_stock')
                    ->label('Stok Aman')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('lead_time')
                    ->label('Waktu Pemesanan')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('daily_usage')
                    ->label('Daily Usage')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reorder_point')
                    ->label('ROP')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn ($record) => $record->stock <= $record->reorder_point ? 'danger' : 'success'),
                TextColumn::make('created_at')
                    ->label('Dibuat Sejak')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer'])),
                TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer'])),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                Action::make('export_pdf')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-m-document-arrow-down')
                    ->color('danger')
                    ->visible(fn () => auth()->user()?->hasRole('manajer'))
                    ->action(function () {
                        $products = Product::with('category')->latest()->get();
                        $pdf = Pdf::loadView('pdf.product-report', ['products' => $products]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-daftar-barang.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
