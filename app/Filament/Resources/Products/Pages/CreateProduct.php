<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Tambah Barang';
    }
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan')
            ->color('primary');
            
    }
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Tambah Lagi')
            ->color('gray');
    }
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal')
            ->color('danger');
    }

}
