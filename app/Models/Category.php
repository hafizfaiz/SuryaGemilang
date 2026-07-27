<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{

    protected $fillable = [
        'category_name',
        'type_name',
        'created_by',
        'updated_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->created_by = $category->created_by ?? auth()->id();
            $category->updated_by = auth()->id();
        });

        static::updating(function (Category $category) {
            $category->updated_by = auth()->id();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}