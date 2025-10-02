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

    public function meterReadings()
    {
        return $this->hasMany(MeterReading::class);
    }

    public function latestMeterReading()
    {
        return $this->hasOne(MeterReading::class)->latestOfMany('reading_date');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByFuel($query, $fuelId)
    {
        return $query->where('fuel_id', $fuelId);
    }

    // Helper Methods
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function getLastClosingReading(): float
    {
        return $this->meterReadings()
            ->latest('reading_date')
            ->latest('reading_time')
            ->value('closing_reading') ?? 0.0;
    }

    public function getTodaysSales(): float
    {
        return $this->meterReadings()
            ->whereDate('reading_date', today())
            ->sum('total_dispensed');
    }

    public function getTodaysRevenue(): float
    {
        return $this->meterReadings()
            ->whereDate('reading_date', today())
            ->sum('total_amount');
    }

    public function getTotalSalesThisMonth(): float
    {
        return $this->meterReadings()
            ->whereMonth('reading_date', now()->month)
            ->whereYear('reading_date', now()->year)
            ->sum('total_dispensed');
    }

    public function getRevenueThisMonth(): float
    {
        return $this->meterReadings()
            ->whereMonth('reading_date', now()->month)
            ->whereYear('reading_date', now()->year)
            ->sum('total_amount');
    }

    public function hasReadingsToday(): bool
    {
        return $this->meterReadings()
            ->whereDate('reading_date', today())
            ->exists();
    }

    public function getStatusBadgeColor(): string
    {
        return $this->is_active ? 'green' : 'red';
    }

    public function getStatusText(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
}
