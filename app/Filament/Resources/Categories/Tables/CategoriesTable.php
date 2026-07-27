<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Category;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_name')
                    ->label('Kategori Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type_name')
                    ->label('Merek')
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
            ->toolbarActions([
                Action::make('export_pdf')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-m-document-arrow-down')
                    ->color('danger')
                    ->visible(fn () => auth()->user()?->hasRole('manajer'))
                    ->action(function () {
                        $categories = Category::latest()->get();
                        $pdf = Pdf::loadView('pdf.category-report', ['categories' => $categories]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-kategori-barang.pdf');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
