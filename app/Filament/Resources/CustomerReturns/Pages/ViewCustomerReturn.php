<?php

namespace App\Filament\Resources\CustomerReturns\Pages;

use App\Filament\Resources\CustomerReturns\CustomerReturnResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerReturn extends ViewRecord
{
    protected static string $resource = CustomerReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
