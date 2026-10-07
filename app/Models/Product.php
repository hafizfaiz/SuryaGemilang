<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'type_name',
        'product_name',
        'price',
        'cost_price',
        'stock',
        'safety_stock',
        'daily_usage',
        'lead_time',
        'reorder_point',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'safety_stock' => 'integer',
        'daily_usage' => 'decimal:2',
        'lead_time' => 'integer',
        'reorder_point' => 'integer',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Product $product) {
            $product->created_by = $product->created_by ?? auth()->id();
            $product->updated_by = auth()->id();
        });

        static::updating(function (Product $product) {
            $product->updated_by = auth()->id();
        });
        //rop
        static::saving(function (Product $product) {
            $dailyUsage = $product->exists
                ? $product->calculateDailyUsage()
                : (float) ($product->daily_usage ?? 0);
            $leadTime = max(
                0,
                (int) ($product->lead_time ?? 15)
            );
            $safetyStock = max(
                0,
                (int) ($product->safety_stock ?? 0)
            );
            $product->daily_usage = $dailyUsage;
            $product->lead_time = $leadTime;
            $product->reorder_point = (int) ceil(
                ($dailyUsage * $leadTime)
                +
                $safetyStock
            );
        });
    }

    public function calculateDailyUsage(): float
    {
        if (! $this->id || ! $this->created_at) {
            return 0;
        }
        $totalSold = Sales::query()
            ->get(['products'])
            ->flatMap(fn (Sales $sale) => $sale->products ?? [])
            ->filter(
                fn (array $item) =>
                (int) ($item['product_id'] ?? 0)
                ===
                (int) $this->id
            )
            ->sum(
                fn (array $item) =>
                (int) ($item['quantity'] ?? 0)
            );
        $days = max(
            1,
            (int) $this->created_at
                ->diffInDays(now())
        );
        return round(
            $totalSold / $days,
            2
        );

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(
            StockIn::class
        );
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(
            StockOut::class
        );
    }
}