<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(function () {
                        return Category::query()
                            ->orderBy('category_name')
                            ->get()
                            ->unique(function ($category) {
                                return strtolower($category->category_name);
                            })
                            ->pluck('category_name', 'id');
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $old) {
                        $oldCategory = Category::find($old);
                        $oldCategoryName = $oldCategory ? trim($oldCategory->category_name) : '';
                        $oldTypeName = trim((string) $get('type_name'));

                        $newCategory = Category::find($state);
                        $newCategoryName = $newCategory ? trim($newCategory->category_name) : '';

                        $oldPrefix = trim("{$oldCategoryName} {$oldTypeName}");
                        if ($oldPrefix === '') {
                            $oldPrefix = trim($oldCategoryName);
                        }

                        $currentName = trim((string) $get('product_name'));
                        $remainder = $currentName;
                        if ($oldPrefix !== '' && str_starts_with(strtolower($currentName), strtolower($oldPrefix))) {
                            $remainder = trim(substr($currentName, strlen($oldPrefix)));
                        } elseif ($oldCategoryName !== '' && str_starts_with(strtolower($currentName), strtolower($oldCategoryName))) {
                            $remainder = trim(substr($currentName, strlen($oldCategoryName)));
                        }

                        $set('type_name', null);

                        if ($newCategoryName !== '') {
                            $newPrefix = $newCategoryName;
                            $set('product_name', $remainder !== '' ? "{$newPrefix} {$remainder}" : "{$newPrefix} ");
                        } else {
                            $set('product_name', $remainder);
                        }
                    })
                    ->required(),

                Select::make('type_name')
                    ->label('Merek')
                    ->options(function (Get $get) {
                        $categoryId = $get('category_id');
                        if (! $categoryId) {
                            return [];
                        }
                        $category = Category::find($categoryId);
                        if (! $category) {
                            return [];
                        }
                        return Category::query()
                            ->whereRaw('LOWER(category_name) = ?', [strtolower(trim($category->category_name))])
                            ->pluck('type_name', 'type_name')
                            ->unique();
                    })
                    ->searchable()
                    ->live()
                    ->disabled(fn (Get $get) => ! $get('category_id'))
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $old) {
                        $categoryId = $get('category_id');
                        $category = Category::find($categoryId);
                        $categoryName = $category ? trim($category->category_name) : '';

                        $oldTypeName = trim((string) $old);
                        $newTypeName = trim((string) $state);

                        $oldPrefix = trim("{$categoryName} {$oldTypeName}");
                        if ($oldPrefix === '') {
                            $oldPrefix = trim($categoryName);
                        }

                        $currentName = trim((string) $get('product_name'));
                        $remainder = $currentName;
                        if ($oldPrefix !== '' && str_starts_with(strtolower($currentName), strtolower($oldPrefix))) {
                            $remainder = trim(substr($currentName, strlen($oldPrefix)));
                        } elseif ($categoryName !== '' && str_starts_with(strtolower($currentName), strtolower($categoryName))) {
                            $remainder = trim(substr($currentName, strlen($categoryName)));
                        }

                        $newPrefix = trim("{$categoryName} {$newTypeName}");
                        if ($newPrefix === '') {
                            $newPrefix = trim($categoryName);
                        }

                        if ($newPrefix !== '') {
                            $set('product_name', $remainder !== '' ? "{$newPrefix} {$remainder}" : "{$newPrefix} ");
                        } else {
                            $set('product_name', $remainder);
                        }
                    })
                    ->required(),

                TextInput::make('product_name')
                    ->label('Product Name')
                    ->placeholder('Contoh: Ban Honda 90/90 1 Set')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->label('Harga Jual')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('cost_price')
                    ->label('Harga Modal (HPP)')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->placeholder('Opsional (Default: 70% harga jual)'),

                TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('safety_stock')
                    ->label('Stok Aman')
                    ->numeric()
                    ->minValue(0)
                    ->default(10)
                    ->required(),

                TextInput::make('lead_time')
                    ->label('Waktu Pemesanan (Hari)')
                    ->numeric()
                    ->minValue(0)
                    ->default(3)
                    ->suffix('hari')
                    ->required(),
            ]);
    }
}