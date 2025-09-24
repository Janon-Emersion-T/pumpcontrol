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
        'price_per_liter',
        'reading_date',
        'reading_time',
        'shift',
        'notes',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'opening_reading' => 'decimal:3',
        'closing_reading' => 'decimal:3',
        'total_dispensed' => 'decimal:3',
        'price_per_liter' => 'decimal:3',
        'total_amount' => 'decimal:2',
        'reading_date' => 'date',
        'reading_time' => 'string',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Boot method to automatically calculate totals
    protected static function booted()
    {
        static::saving(function ($meterReading) {
            $meterReading->total_dispensed = max(0, $meterReading->closing_reading - $meterReading->opening_reading);
            $meterReading->total_amount = $meterReading->total_dispensed * $meterReading->price_per_liter;
        });
    }

    // Relationships
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

    // Scopes
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

    // Helper: Get last closing reading for a pump
    public static function lastClosingReading(int $pumpId): float
    {
        $lastReading = self::where('pump_id', $pumpId)
                           ->latest('reading_date')
                           ->latest('reading_time')
                           ->first();

        return $lastReading ? $lastReading->closing_reading : 0.0;
    }

    // Optional: Manual calculation
    public static function calculateTotals($opening, $closing, $price)
    {
        $totalDispensed = max(0, $closing - $opening);
        $totalAmount = $totalDispensed * $price;
        return [$totalDispensed, $totalAmount];
    }
}
