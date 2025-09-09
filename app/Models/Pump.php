<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fuel_id',
        'is_active',
    ];

    public function fuel()
    {
        return $this->belongsTo(Fuel::class);
    }
   
    public function currentFuel()
    {
        return $this->hasOne(\App\Models\CurrentFuelLevel::class);
    }


}
