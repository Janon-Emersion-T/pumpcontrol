<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    protected $fillable = [
        'pump_id',
        'fuel_id',
        'user_id',
        'opening_reading',
        'closing_reading',
        'total_dispensed',
        'price_per_liter',
        'total_amount',
        'reading_date',
        'reading_time',
        'shift',
        'notes',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'opening_reading' => 'decimal:3',
            'closing_reading' => 'decimal:3',
            'total_dispensed' => 'decimal:3',
            'price_per_liter' => 'decimal:3',
            'total_amount' => 'decimal:2',
            'reading_date' => 'date',
            'reading_time' => 'datetime:H:i',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class);
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('reading_date', today());
    }

    public function scopeByShift($query, string $shift)
    {
        return $query->where('shift', $shift);
    }

    public function scopeByPump($query, int $pumpId)
    {
        return $query->where('pump_id', $pumpId);
    }

    public function scopeByFuel($query, int $fuelId)
    {
        return $query->where('fuel_id', $fuelId);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }
}
