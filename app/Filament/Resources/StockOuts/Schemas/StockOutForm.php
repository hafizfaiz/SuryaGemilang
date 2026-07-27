<?php

namespace App\Filament\Resources\StockOuts\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockOutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Product')
                    ->options(function () {
                        return Product::query()
                            ->get()
                            ->mapWithKeys(function ($product) {
                                return [
                                    $product->id => $product->product_name . ' | Stock: ' . $product->stock,
                                ];
                            });
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('out_quantity')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Select::make('out_reason')
                    ->label('Alasan Keluar')
                    ->options([
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
            ]);
    }
}