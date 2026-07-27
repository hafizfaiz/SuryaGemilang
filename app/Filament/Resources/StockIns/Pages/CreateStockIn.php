<?php

namespace App\Filament\Resources\StockIns\Pages;

use App\Filament\Resources\StockIns\StockInResource;
use App\Models\Product;
use App\Models\StockIn;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateStockIn extends CreateRecord
{
    protected static string $resource = StockInResource::class;

    public function getTitle(): string
    {
        return 'Tambah Barang Masuk';
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

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            $stockIn = StockIn::create([
                'product_id' => $data['product_id'],
                'return_id' => null,
                'user_id' => auth()->id(),
                'in_quantity' => $data['in_quantity'],
                'in_reason' => $data['in_reason'],
            ]);

            $productToUpdate = Product::find($data['product_id']);
            if ($productToUpdate) {
                $productToUpdate->stock += $data['in_quantity'];
                $productToUpdate->save();
            }

            return $stockIn;
        });
    }
}