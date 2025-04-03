<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'type',              // Type of sale (normal or insurance)
        'coverage_type',     // Coverage type (full or partial)
        'shifa_card_number', // Shifa card number (nullable)
        'total_amount',      // Total amount of the sale
        'covered_amount',    // Amount covered by insurance
        'patient_pays',      // Amount the patient pays
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
