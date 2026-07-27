<?php

namespace App\Filament\Resources\CustomerReturns\Schemas;

use App\Models\Product;
use App\Models\Sales;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CustomerReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Select::make('sales_id')
                    ->label('Sales / Invoice')
                    ->options(function () {
                        return Sales::query()
                            ->latest()
                            ->get()
                            ->mapWithKeys(function ($sale) {
                                return [
                                    $sale->id => $sale->invoice_number . ' - ' . $sale->customer_name,
                                ];
                            });
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('returns', []);
                    })
                    ->required()
                    ->columnSpanFull(),

                Repeater::make('returns')
                    ->label('Daftar Barang Return')
                    ->schema([

                        Select::make('product_id')
                            ->label('Product')
                            ->options(function (Get $get, Select $component) {
                                $salesId = $get('../../sales_id');

                                if (! $salesId) {
                                    return [];
                                }

                                $sale = Sales::find($salesId);

                                if (! $sale || empty($sale->products)) {
                                    return [];
                                }

                                $allRepeaterRows = $get('../../returns') ?? [];
                                $statePath = $component->getStatePath();
                                $currentProductId = $get('product_id');

                                $options = [];

                                $productIds = collect($sale->products)
                                    ->pluck('product_id')
                                    ->filter()
                                    ->unique()
                                    ->values();

                                $products = Product::query()
                                    ->whereIn('id', $productIds)
                                    ->get()
                                    ->keyBy('id');

                                foreach ($productIds as $productId) {
                                    $product = $products->get($productId);
                                    if (! $product) {
                                        continue;
                                    }

                                    $soldQuantity = collect($sale->products)
                                        ->where('product_id', $productId)
                                        ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));

                                    $alreadyReturnedInDb = \App\Models\CustomerReturn::query()
                                        ->where('sales_id', $salesId)
                                        ->where('product_id', $productId)
                                        ->sum('return_quantity');

                                    $usedInOtherRows = 0;
                                    if (is_array($allRepeaterRows)) {
                                        foreach ($allRepeaterRows as $rowKey => $rowItem) {
                                            $isCurrentRow = str_contains($statePath, (string) $rowKey);
                                            if (! $isCurrentRow && (($rowItem['product_id'] ?? null) == $productId)) {
                                                $usedInOtherRows += (int) ($rowItem['return_quantity'] ?? 0);
                                            }
                                        }
                                    }

                                    $remaining = $soldQuantity - $alreadyReturnedInDb - $usedInOtherRows;

                                    if ($remaining <= 0 && $currentProductId != $productId) {
                                        continue;
                                    }

                                    $options[$product->id] = "{$product->product_name} | Sisa Bisa Retur: " . max(0, $remaining) . " (Terjual: {$soldQuantity})";
                                }

                                return $options;
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state, Select $component) {
                                if (! $state) {
                                    return;
                                }
                                $salesId = $get('../../sales_id');
                                if (! $salesId) {
                                    return;
                                }
                                $sale = Sales::find($salesId);
                                if (! $sale || empty($sale->products)) {
                                    return;
                                }
                                $soldQuantity = collect($sale->products)
                                    ->where('product_id', $state)
                                    ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
                                $alreadyReturnedInDb = \App\Models\CustomerReturn::query()
                                    ->where('sales_id', $salesId)
                                    ->where('product_id', $state)
                                    ->sum('return_quantity');

                                $allRepeaterRows = $get('../../returns') ?? [];
                                $statePath = $component->getStatePath();
                                $usedInOtherRows = 0;
                                if (is_array($allRepeaterRows)) {
                                    foreach ($allRepeaterRows as $rowKey => $rowItem) {
                                        $isCurrentRow = str_contains($statePath, (string) $rowKey);
                                        if (! $isCurrentRow && (($rowItem['product_id'] ?? null) == $state)) {
                                            $usedInOtherRows += (int) ($rowItem['return_quantity'] ?? 0);
                                        }
                                    }
                                }
                                $remaining = max(1, $soldQuantity - $alreadyReturnedInDb - $usedInOtherRows);
                                $currentQty = (int) ($get('return_quantity') ?? 1);
                                if ($currentQty > $remaining) {
                                    $set('return_quantity', $remaining);
                                }
                            })
                            ->required(),

                        TextInput::make('return_quantity')
                            ->label('Return Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->maxValue(function (Get $get, TextInput $component) {
                                $salesId = $get('../../sales_id');
                                $productId = $get('product_id');
                                if (! $salesId || ! $productId) {
                                    return 999999;
                                }
                                $sale = Sales::find($salesId);
                                if (! $sale || empty($sale->products)) {
                                    return 999999;
                                }
                                $soldQuantity = collect($sale->products)
                                    ->where('product_id', $productId)
                                    ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
                                $alreadyReturnedInDb = \App\Models\CustomerReturn::query()
                                    ->where('sales_id', $salesId)
                                    ->where('product_id', $productId)
                                    ->sum('return_quantity');

                                $allRepeaterRows = $get('../../returns') ?? [];
                                $statePath = $component->getStatePath();
                                $usedInOtherRows = 0;
                                if (is_array($allRepeaterRows)) {
                                    foreach ($allRepeaterRows as $rowKey => $rowItem) {
                                        $isCurrentRow = str_contains($statePath, (string) $rowKey);
                                        if (! $isCurrentRow && (($rowItem['product_id'] ?? null) == $productId)) {
                                            $usedInOtherRows += (int) ($rowItem['return_quantity'] ?? 0);
                                        }
                                    }
                                }

                                return max(1, $soldQuantity - $alreadyReturnedInDb - $usedInOtherRows);
                            })
                            ->live()
                            ->required(),

                        Select::make('return_reason')
                            ->label('Return Reason')
                            ->options([
                                'tidak_sesuai' => 'Barang Tidak Sesuai',
                                'barang_cacat' => 'Barang Cacat',
                            ])
                            ->required(),

                        Select::make('return_condition')
                            ->label('Return Condition')
                            ->options([
                                'layak' => 'Layak',
                                'tidak_layak' => 'Tidak Layak',
                            ])
                            ->required(),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->minItems(1)
                    ->addActionLabel('Tambah Barang Return')
                    ->live()
                    ->required()
                    ->dehydrated()
                    ->columnSpanFull(),
            ]);
    }
}