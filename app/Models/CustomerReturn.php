<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerReturn extends Model
{
    protected $fillable = [
        'sales_id',
        'product_id',
        'user_id',
        'return_quantity',
        'return_reason',
        'return_condition',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id');
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