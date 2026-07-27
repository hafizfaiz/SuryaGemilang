<?php

namespace App\Filament\Resources\CustomerReturns\Pages;

use App\Filament\Resources\CustomerReturns\CustomerReturnResource;
use Filament\Actions\Action;
use App\Models\CustomerReturn;
use App\Models\Product;
use App\Models\Sales;
use App\Models\StockIn;
use App\Models\StockOut;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateCustomerReturn extends CreateRecord
{
    protected static string $resource = CustomerReturnResource::class;

    public function getTitle(): string
    {
        return 'Tambah Barang Pengembalian';
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
            

            if (empty($data['returns']) || ! is_array($data['returns'])) {
                throw ValidationException::withMessages([
                    'returns' => 'Daftar barang return wajib diisi.',
                ]);
            }

            $sale = Sales::findOrFail($data['sales_id']);

            $requestedReturns = collect($data['returns'])
                ->filter(fn ($item) => ! empty($item['product_id']))
                ->groupBy('product_id')
                ->map(function ($rows) {
                    return [
                        'product_id' => (int) $rows->first()['product_id'],
                        'return_quantity' => $rows->sum(fn ($row) => (int) ($row['return_quantity'] ?? 0)),
                    ];
                })
                ->values();

            foreach ($requestedReturns as $requestedItem) {
                $soldQuantity = collect($sale->products)
                    ->where('product_id', $requestedItem['product_id'])
                    ->sum(fn ($saleItem) => (int) ($saleItem['quantity'] ?? 0));

                $alreadyReturned = CustomerReturn::query()
                    ->where('sales_id', $sale->id)
                    ->where('product_id', $requestedItem['product_id'])
                    ->sum('return_quantity');

                if ($soldQuantity <= 0) {
                    throw ValidationException::withMessages([
                        'returns' => 'Produk yang dipilih tidak ada di invoice sales.',
                    ]);
                }

                if (($alreadyReturned + $requestedItem['return_quantity']) > $soldQuantity) {
                    $product = Product::find($requestedItem['product_id']);

                    throw ValidationException::withMessages([
                        'returns' => 'Jumlah return untuk ' . ($product?->product_name ?? 'produk ini') .
                            ' melebihi jumlah terjual. Terjual: ' . $soldQuantity .
                            ', sudah return: ' . $alreadyReturned .
                            ', diminta: ' . $requestedItem['return_quantity'] . '.',
                    ]);
                }
            }

            $firstReturn = null;

            foreach ($data['returns'] as $item) {
                if (
                    empty($item['product_id']) ||
                    empty($item['return_quantity']) ||
                    empty($item['return_reason']) ||
                    empty($item['return_condition'])
                ) {
                    throw ValidationException::withMessages([
                        'returns' => 'Semua data barang return wajib diisi.',
                    ]);
                }

                $customerReturn = CustomerReturn::create([
                    'sales_id' => $data['sales_id'],
                    'product_id' => $item['product_id'],
                    'user_id' => auth()->id(),
                    'return_quantity' => $item['return_quantity'],
                    'return_reason' => $item['return_reason'],
                    'return_condition' => $item['return_condition'],
                ]);

                if ($firstReturn === null) {
                    $firstReturn = $customerReturn;
                }

                $stockDestination = $this->getStockDestination(
                    $item['return_reason'],
                    $item['return_condition']
                );

                if ($stockDestination === 'stock_in') {
                    StockIn::create([
                        'product_id' => $customerReturn->product_id,
                        'return_id' => $customerReturn->id,
                        'user_id' => auth()->id(),
                        'in_quantity' => $customerReturn->return_quantity,
                        'in_reason' => 'Retur Customer',
                    ]);

                    $productToUpdate = Product::find($customerReturn->product_id);
                    if ($productToUpdate) {
                        $productToUpdate->stock += $customerReturn->return_quantity;
                        $productToUpdate->save();
                    }
                }

                if ($stockDestination === 'stock_out') {
                    StockOut::create([
                        'sales_id' => $customerReturn->sales_id,
                        'return_id' => $customerReturn->id,
                        'product_id' => $customerReturn->product_id,
                        'user_id' => auth()->id(),
                        'out_quantity' => $customerReturn->return_quantity,
                        'out_reason' => 'Retur Customer',
                    ]);
                }
            }

            if ($firstReturn === null) {
                throw ValidationException::withMessages([
                    'returns' => 'Data return gagal dibuat.',
                ]);
            }

            return $firstReturn;
        });
    }

    private function getStockDestination(string $returnReason, string $returnCondition): string
    {
        if (
            $returnReason === 'barang_cacat' ||
            $returnCondition === 'tidak_layak'
        ) {
            return 'stock_out';
        }

        return 'stock_in';
    }
    
}