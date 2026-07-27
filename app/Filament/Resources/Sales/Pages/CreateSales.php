<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SalesResource;
use Filament\Actions\Action;    
use App\Models\Product;
use App\Models\Sales;
use App\Models\StockOut;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class CreateSales extends CreateRecord
{
    protected static string $resource = SalesResource::class;

    public function getTitle(): string
    {
        return 'Buat Pesanan';
    }
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Checkout')
            ->color('primary');
            
    }
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Checkout & Transaksi Baru')
            ->color('gray');
    }
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal')
            ->color('danger');
    }

    protected function handleRecordCreation(array $data): Sales
    {
        return DB::transaction(function () use ($data) {

            if (empty($data['products']) || ! is_array($data['products'])) {
                throw ValidationException::withMessages([
                    'products' => 'Daftar barang wajib diisi.',
                ]);
            }

            $items = collect($data['products'])
                ->filter(fn ($item) => ! empty($item['product_id']))
                ->groupBy('product_id')
                ->map(function ($rows) {
                    return [
                        'product_id' => (int) $rows->first()['product_id'],
                        'quantity' => $rows->sum(fn ($row) => (int) ($row['quantity'] ?? 0)),
                    ];
                })
                ->values();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'products' => 'Daftar barang wajib diisi.',
                ]);
            }

            $products = Product::query()
                ->whereIn('id', $items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $normalizedProducts = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'products' => 'Produk tidak ditemukan.',
                    ]);
                }

                if ($item['quantity'] <= 0) {
                    throw ValidationException::withMessages([
                        'products' => 'Quantity harus lebih dari 0.',
                    ]);
                }

                if ($item['quantity'] > $product->stock) {
                    Notification::make()
                        ->danger()
                        ->title('Gagal Checkout, Jumlah Stok Kurang')
                        ->send();

                    throw new Halt();
                }

                $subtotal = $item['quantity'] * $product->price;
                $total += $subtotal;

                $normalizedProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $sale = Sales::create([
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'],
                'products' => $normalizedProducts,
                'total_price' => $total,
            ]);

            foreach ($normalizedProducts as $item) {
                StockOut::create([
                    'sales_id' => $sale->id,
                    'return_id' => null,
                    'product_id' => $item['product_id'],
                    'user_id' => auth()->id(),
                    'out_quantity' => $item['quantity'],
                    'out_reason' => 'Penjualan',
                ]);

                $productToUpdate = Product::find($item['product_id']);
                if ($productToUpdate) {
                    $productToUpdate->stock = max(0, $productToUpdate->stock - $item['quantity']);
                    $productToUpdate->save();
                }
            }

            return $sale;
        });
    }

    protected function getRedirectUrl(): string
    {
        return route('sales.receipt', ['sale' => $this->record->id]);
    }
}