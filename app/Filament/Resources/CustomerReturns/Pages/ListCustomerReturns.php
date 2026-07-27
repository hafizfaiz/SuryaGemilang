<?php

namespace App\Filament\Resources\CustomerReturns\Pages;

use App\Filament\Resources\CustomerReturns\CustomerReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerReturns extends ListRecords
{
    protected static string $resource = CustomerReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah'),
        ];
    }
}
