<?php

namespace App\Filament\Resources\StockIns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StockIn;
use Filament\Tables\Table;

class StockInsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.category.category_name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.type_name')
                    ->label('Merek')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.product_name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('in_quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('in_reason')
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
                    ->visible(fn () => auth()->user()?->hasRole('manajer'))
                    ->action(function () {
                        $stockIns = StockIn::with(['product.category'])->latest()->get();
                        $pdf = Pdf::loadView('pdf.stock-in-report', ['stockIns' => $stockIns]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-barang-masuk.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
