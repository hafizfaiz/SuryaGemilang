<?php

namespace App\Filament\Resources\StockOuts\Pages;

use App\Filament\Resources\StockOuts\StockOutResource;
use App\Models\Product;
use App\Models\StockOut;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class CreateStockOut extends CreateRecord
{
    protected static string $resource = StockOutResource::class;

    public function getTitle(): string
    {
        return 'Tambah Barang Keluar';
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

            $product = Product::query()
                ->where('id', $data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $data['out_quantity'] > $product->stock) {
                Notification::make()
                    ->danger()
                    ->title('Gagal Mengurangi Stok, Jumlah Stok Kurang')
                    ->send();

                throw new Halt();
            }

            $stockOut = StockOut::create([
                'sales_id' => null,
                'return_id' => null,
                'product_id' => $data['product_id'],
                'user_id' => auth()->id(),
                'out_quantity' => $data['out_quantity'],
                'out_reason' => $data['out_reason'],
            ]);

            $product->stock = max(0, $product->stock - $data['out_quantity']);
            $product->save();

            return $stockOut;
        });
    }
}