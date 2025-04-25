<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_name',
        'patient_name',
        'patient_phone',
        'notes',
        'status',
    ];

    /**
     * Get the medications associated with the prescription.
     */
    public function medications()
    {
        return $this->hasMany(PrescriptionMedication::class);
    }

    /**
     * Get the sale associated with the prescription.
     */
    public function sale()
    {
        return $this->hasOne(Sale::class);
    }
}
