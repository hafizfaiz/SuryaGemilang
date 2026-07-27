<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
        
    public function getTitle(): string
    {
        return 'Tambah Kategori Barang';
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
