<?php

namespace App\Filament\Resources\CustomerReturns\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerReturnInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sale.invoice_number')
                    ->label('Invoice No')
                    ->placeholder('-'),
                TextEntry::make('sale.customer_name')
                    ->label('Nama Pembeli')
                    ->placeholder('-'),
                TextEntry::make('product.product_name')
                    ->label('Nama Barang')
                    ->placeholder('-'),
                TextEntry::make('sold_quantity')
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
                    ->color('info'),
                TextEntry::make('return_quantity')
                    ->label('Jumlah Retur')
                    ->placeholder('-')
                    ->badge()
                    ->color('danger'),
                TextEntry::make('return_reason')
                    ->label('Alasan')
                    ->placeholder('-'),
                TextEntry::make('return_condition')
                    ->label('Kondisi')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Dibuat Sejak')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
