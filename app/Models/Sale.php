<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'type', 
        "sale_session_id",  
        'prescription_id',           
        'total_amount',      // Total amount of the sale
        'covered_amount',    // Amount covered by insurance
        'patient_pays',// Amount the patient pays
        'status',
              
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function session()
{
    return $this->belongsTo(SaleSession::class, 'sale_session_id');
}
public function prescription()
{
    return $this->belongsTo(Prescription::class);
}


}
