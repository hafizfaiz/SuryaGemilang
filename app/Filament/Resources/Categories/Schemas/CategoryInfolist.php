<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category_name')
                    ->label('Kategori')
                    ->placeholder('-'),
                TextEntry::make('type_name')
                    ->label('Merek')
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
