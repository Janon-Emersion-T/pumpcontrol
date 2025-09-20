<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_litre',
        'stock_litres',
        'description',
    ];

    public function pumps()
    {
        return $this->hasMany(Pump::class);
    }

    public function meterReadings()
    {
        return $this->hasMany(MeterReading::class);
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
