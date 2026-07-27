<?php

namespace App\Filament\Resources\CustomerReturns\Pages;

use App\Filament\Resources\CustomerReturns\CustomerReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerReturn extends EditRecord
{
    protected static string $resource = CustomerReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
