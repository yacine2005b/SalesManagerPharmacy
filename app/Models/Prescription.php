<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'notes',
        'duration',
        'prescription_date',
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

    /**
     * Get the doctor associated with the prescription.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient associated with the prescription.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
