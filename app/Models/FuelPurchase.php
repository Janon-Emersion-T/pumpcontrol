<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'pump_id',
        'fuel_id',
        'supplier_id',
        'user_id',
        'liters',
        'price_per_liter',
        'total_cost',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:4',
        'total_cost' => 'decimal:2',
        'purchase_date' => 'date',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
