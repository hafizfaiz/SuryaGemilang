<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SalesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice_number')
                    ->label('Invoice Number'),

                TextEntry::make('customer_name')
                    ->label('Customer Name'),

                TextEntry::make('products')
                    ->label('Daftar Barang & Subtotal')
                    ->getStateUsing(function ($record) {
                        $products = is_string($record->products)
                            ? json_decode($record->products, true)
                            : $record->products;

                        if (!is_array($products)) {
                            return '-';
                        }

                        return collect($products)
                            ->map(function ($item) {
                                $product = \App\Models\Product::find($item['product_id'] ?? 0);
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
                    ->columnSpanFull(),

                TextEntry::make('total_price')
                    ->label('Total Price')
                    ->money('IDR'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}