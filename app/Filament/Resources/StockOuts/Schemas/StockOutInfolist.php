<?php

namespace App\Filament\Resources\StockOuts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockOutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('out_reason'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
