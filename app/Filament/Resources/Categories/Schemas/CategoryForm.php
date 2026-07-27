<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_name')
                    ->label('Kategori Barang')
                    ->datalist(function () {
                        return Category::query()
                            ->distinct()
                            ->pluck('category_name')
                            ->filter()
                            ->toArray();
                    })
                    ->required(),
                TextInput::make('type_name')
                    ->label('Merek')
                    ->datalist(function () {
                        return Category::query()
                            ->distinct()
                            ->pluck('type_name')
                            ->filter()
                            ->toArray();
                    })
                    ->required(),
            ]);
    }
}
