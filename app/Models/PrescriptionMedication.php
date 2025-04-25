<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedication extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'product_id',
        'quantity',
    ];

    /**
     * Get the prescription associated with the medication.
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the product associated with the medication.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
