<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
      
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function shifaCard()
    {
        return $this->hasOne(ShifaCard::class);
    }
}
