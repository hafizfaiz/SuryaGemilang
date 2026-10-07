<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id()),


                Hidden::make('invoice_number')
                    ->label('Invoice Number')
                    ->disabled()
                    ->dehydrated(false),


                TextInput::make('customer_name')
                    ->label('Nama Pembeli')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->columnSpan(1),

                Select::make('region')
                    ->label('Wilayah / Region')
                    ->options([
                        'Jawa Barat' => 'Jawa Barat',
                        'Jabodetabek' => 'Jabodetabek',
                        'Jawa Tengah' => 'Jawa Tengah',
                        'Jawa Timur' => 'Jawa Timur',
                        'Banten' => 'Banten',
                        'Sumatera' => 'Sumatera',
                        'Bali & Nusa Tenggara' => 'Bali & Nusa Tenggara',
                        'Kalimantan & Sulawesi' => 'Kalimantan & Sulawesi',
                    ])
                    ->default('Jawa Barat')
                    ->required()
                    ->columnSpan(1),

                Select::make('customer_segment')
                    ->label('Customer Segment')
                    ->options([
                        'Retail' => 'Retail (Eceran)',
                        'Grosir' => 'Grosir (Wholesale)',
                        'Bengkel' => 'Bengkel (Reseller)',
                        'Corporate' => 'Corporate (B2B)',
                    ])
                    ->default('Retail')
                    ->required()
                    ->columnSpan(1),



                Repeater::make('products')
                    ->label('Daftar Barang')
                    ->schema([
                        Select::make('product_id')
                            ->label('Nama Barang')
                            ->options(function () {
                                return Product::query()
                                    ->get()
                                    ->mapWithKeys(function ($product) {
                                        return [
                                            $product->id =>
                                                $product->product_name .
                                                ' | Stock: ' .
                                                $product->stock,
                                        ];
                                    });
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set){

                                $product = Product::find($state);

                                if ($product) {

                                    $set(
                                        'price',
                                        $product->price
                                    );
                                }

                            })
                            ->required(),


                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live()
                            ->required(),


                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                    ])
            ->columns(3)
            ->defaultItems(1)
            ->minItems(1)
            ->addActionLabel('Tambah Barang')
            ->live()
            ->afterStateUpdated(function ($state, callable $set){
                $total = collect($state)
                    ->sum(function ($item){
                        return 
                            ((int) ($item['quantity'] ?? 0)) *
                            ((float) ($item['price'] ?? 0));
                    });
                $set(
                    'total_price',
                    $total
                );
            })
        ->columnSpanFull(),
        ]);
    }
}