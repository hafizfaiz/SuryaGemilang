<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    protected $fillable = [
        'sales_id',
        'return_id',
        'product_id',
        'user_id',
        'out_quantity',
        'out_reason',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function customerReturn(): BelongsTo
    {
        return $this->belongsTo(CustomerReturn::class, 'return_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}