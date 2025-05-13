<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',
        'price',
        'barcode',
        'expiration_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
