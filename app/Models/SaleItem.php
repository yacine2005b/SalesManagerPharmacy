<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'lot_id',
        'quantity',
        'original_price',
        'final_price',
        'insurance_covered',
    ];

    // Relationship with Product
    

    // Relationship with Lot
    public function lot()
    {
        return $this->belongsTo(Lot::class, 'lot_id');
    }
public function product()
{
    return $this->belongsTo(Product::class);
}
    // Relationship with Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
