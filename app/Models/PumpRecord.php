<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PumpRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pump_id',
        'fuel_id',
        'record_date',
        'opening_meter',
        'closing_meter',
        'litres_sold',
        'price_per_litre',
        'total_sales',
        'staff_id',
    ];

    protected $casts = [
        'record_date' => 'date',
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'litres_sold' => 'decimal:2',
        'price_per_litre' => 'decimal:2',
        'total_sales' => 'decimal:2',
    ];

    // 🔗 Relationships

    public function pump()
    {
        return $this->belongsTo(Pump::class);
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
