<?php

namespace App\Filament\Resources\StockOuts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StockOut;
use Filament\Tables\Table;

class StockOutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale.invoice_number')
                    ->label('Invoice No')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('product.product_name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('out_quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('out_reason')
                    ->label('Alasan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customerReturn.sale.invoice_number')
                    ->label('Ref Invoice Retur')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'manajer']))
                    ->action(function () {
                        $stockOuts = StockOut::with(['product.category', 'sale'])->latest()->get();
                        $pdf = Pdf::loadView('pdf.stock-out-report', ['stockOuts' => $stockOuts]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-barang-keluar.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
