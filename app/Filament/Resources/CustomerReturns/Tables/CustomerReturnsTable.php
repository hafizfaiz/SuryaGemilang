<?php

namespace App\Filament\Resources\CustomerReturns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerReturn;
use Filament\Tables\Table;

class CustomerReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale.invoice_number')
                    ->label('Invoice No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sale.customer_name')
                    ->label('Nama Pembeli')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.product_name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sold_quantity')
                    ->label('Jumlah Pembelian')
                    ->getStateUsing(function ($record) {
                        $sale = $record->sale;
                        if (! $sale || empty($sale->products)) {
                            return '-';
                        }
                        return collect($sale->products)
                            ->where('product_id', $record->product_id)
                            ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
                    })
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('info'),
                TextColumn::make('return_quantity')
                    ->label('Jumlah Retur')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('return_reason')
                    ->label('Alasan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('return_condition')
                    ->label('Kondisi')
                    ->sortable()
                    ->searchable(),
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
                    ->label('Petugas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer'])),
            ])
            ->filters([])
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
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer']))
                    ->action(function () {
                        $returns = CustomerReturn::with(['sale', 'product'])->latest()->get();
                        $pdf = Pdf::loadView('pdf.customer-return-report', ['returns' => $returns]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-barang-retur.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}