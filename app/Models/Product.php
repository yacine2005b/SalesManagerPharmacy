<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'low_stock_threshold',
        'dosage',
        'dosage_unit',
        'remboursable',
        'total_quantity',
    ];

    public function lots()
    {
        return $this->hasMany(Lot::class);
    }
}
