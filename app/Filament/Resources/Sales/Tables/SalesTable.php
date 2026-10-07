<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sales;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Nama Pembeli')    
                    ->sortable()
                    ->searchable(),
                TextColumn::make('region')
                    ->label('Wilayah')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('customer_segment')
                    ->label('Segmen')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Corporate' => 'success',
                        'Grosir' => 'warning',
                        'Bengkel' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('products')
                    ->label('Daftar Barang')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $products = json_decode(
                            $record->getRawOriginal('products'),
                            true
                        );

                        if (!is_array($products)) {
                            return '-';
                        }

                        return collect($products)
                            ->map(function ($item) {
                                $product = \App\Models\Product::find($item['product_id']);
                                $productName = $product ? $product->product_name : ('Produk ID ' . ($item['product_id'] ?? '-'));
                                $qty = (int) ($item['quantity'] ?? 0);
                                $price = (float) ($item['price'] ?? ($product ? $product->price : 0));
                                $subtotal = $qty * $price;

                                $formattedPrice = 'Rp ' . number_format($price, 0, ',', '.');
                                $formattedSubtotal = 'Rp ' . number_format($subtotal, 0, ',', '.');

                                return "<div class='py-1'><span class='font-medium text-gray-800 dark:text-gray-200'>• {$productName}</span><br><span class='text-xs text-gray-500 dark:text-gray-400'>Qty: {$qty} × {$formattedPrice} = <strong class='text-primary-600 dark:text-primary-400'>{$formattedSubtotal}</strong></span></div>";
                            })
                            ->implode('');
                    })
                    ->html()
                    ->wrap(),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
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
                TextColumn::make('user.name')
                    ->label('Kasir / Petugas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer'])),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
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
                        $sales = Sales::latest()->get();
                        $pdf = Pdf::loadView('pdf.sales-report', ['sales' => $sales]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-penjualan.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
