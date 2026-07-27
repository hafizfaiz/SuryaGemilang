<?php

namespace App\Filament\Resources\StockIns\Pages;

use App\Filament\Resources\StockIns\StockInResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStockIn extends EditRecord
{
    protected static string $resource = StockInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
