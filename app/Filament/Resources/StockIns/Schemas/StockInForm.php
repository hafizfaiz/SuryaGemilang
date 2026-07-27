<?php

namespace App\Filament\Resources\StockIns\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockInForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_name')
                    ->label('Kategori')
                    ->options(\App\Models\Category::query()->pluck('category_name', 'category_name')->unique())
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($set) {
                        $set('type_name', null);
                        $set('product_id', null);
                    })
                    ->searchable(),

                Select::make('type_name')
                    ->label('Tipe')
                    ->options(function ($get) {
                        $query = Product::query();
                        if ($categoryName = $get('category_name')) {
                            $query->whereHas('category', fn($q) => $q->where('category_name', $categoryName));
                        }
                        return $query->pluck('type_name', 'type_name')->unique();
                    })
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn ($set) => $set('product_id', null))
                    ->searchable(),

                Select::make('product_id')
                    ->label('Nama Barang')
                    ->options(function ($get) {
                        $query = Product::query();

                        if ($categoryName = $get('category_name')) {
                            $query->whereHas('category', fn($q) => $q->where('category_name', $categoryName));
                        }

                        if ($typeName = $get('type_name')) {
                            $query->where('type_name', $typeName);
                        }

                        return $query->get()->mapWithKeys(function ($product) {
                            $cat = $product->category ? $product->category->category_name : '-';
                            return [
                                $product->id => "{$product->product_name} | Stok: {$product->stock}"
                            ];
                        });
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('in_quantity')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Select::make('in_reason')
                    ->label('Jenis Transaksi')
                    ->options([
                        'pengadaan' => 'Pengadaan',
                        'penyesuaian' => 'Penyesuaian Stock',
                    ])
                    ->required(),
            ]);
    }
}