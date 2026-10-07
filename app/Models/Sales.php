<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
    
    protected $fillable = [
        'user_id',
        'invoice_number',
        'customer_name',
        'region',
        'customer_segment',
        'products',
        'total_price',
    ];


    protected $casts = [
        'products' => 'array',
        'total_price' => 'decimal:2',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sales) {

            $lastInvoice = self::latest('id')->first();


            if ($lastInvoice) {

                $lastNumber = intval(
                    str_replace('INV-', '', $lastInvoice->invoice_number)
                );


                $nextNumber = $lastNumber + 1;

            } else {

                $nextNumber = 1;

            }


            $sales->invoice_number = 'INV-' . str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customerReturns()
{
    return $this->hasMany(CustomerReturn::class);
}
}