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

    public function currentMeterReading()
    {
        return $this->hasOne(PumpMeterReading::class);
    }

    public function pumpRecords()
    {
        return $this->hasMany(PumpRecord::class);
    }

    public function fuelAdjustments()
    {
        return $this->hasMany(FuelAdjustment::class);
    }

    public function fuelPurchases()
    {
        return $this->hasMany(FuelPurchase::class);
    }
}
