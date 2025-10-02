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

    // Scopes
    public function scopeLowStock($query, $threshold = 1000)
    {
        return $query->where('stock_litres', '<', $threshold);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_litres', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_litres', '>', 0);
    }

    // Helper Methods
    public function isLowStock($threshold = 1000): bool
    {
        return $this->stock_litres < $threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_litres <= 0;
    }

    public function hasSufficientStock(float $requiredLiters): bool
    {
        return $this->stock_litres >= $requiredLiters;
    }

    public function getTotalPurchasesToday(): float
    {
        return $this->fuelPurchases()
            ->whereDate('purchase_date', today())
            ->sum('liters');
    }

    public function getTotalSalesToday(): float
    {
        return $this->meterReadings()
            ->whereDate('reading_date', today())
            ->sum('total_dispensed');
    }

    public function getRevenueToday(): float
    {
        return $this->meterReadings()
            ->whereDate('reading_date', today())
            ->sum('total_amount');
    }

    public function getStockValue(): float
    {
        return $this->stock_litres * $this->price_per_litre;
    }

    public function getFormattedStock(): string
    {
        return number_format($this->stock_litres, 2) . 'L';
    }

    public function getFormattedPrice(): string
    {
        return 'Rs.' . number_format($this->price_per_litre, 2);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'red',
            'low_stock' => 'yellow',
            'in_stock' => 'green',
            default => 'gray',
        };
    }
}
