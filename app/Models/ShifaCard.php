<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShifaCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'card_number',
        'coverage_type',
        'issue_date',
        'expiry_date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
