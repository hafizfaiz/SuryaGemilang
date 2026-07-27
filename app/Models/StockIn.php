<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    protected $fillable = [
        'product_id',
        'return_id',
        'user_id',
        'in_quantity',
        'in_reason',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customerReturn(): BelongsTo
    {
        return $this->belongsTo(CustomerReturn::class, 'return_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}