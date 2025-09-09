<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pump_id',
        'fuel_id',
        'user_id',
        'liters',
        'type',
        'reason',
        'adjusted_at',
    ];

    protected $casts = [
        'liters' => 'decimal:2',
        'adjusted_at' => 'date',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
