<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelPriceHistory extends Model
{
    use HasFactory;

    protected $table = 'fuel_price_history';

    protected $fillable = [
        'fuel_id',
        'price_per_litre',
        'effective_date',
        'user_id',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'price_per_litre' => 'decimal:2',
        'effective_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForFuel($query, int $fuelId)
    {
        return $query->where('fuel_id', $fuelId);
    }

    public function scopeEffectiveOn($query, $date)
    {
        return $query->where('effective_date', '<=', $date)
            ->orderBy('effective_date', 'desc');
    }

    // Helper Methods
    public static function getCurrentPrice(int $fuelId): ?float
    {
        $price = self::forFuel($fuelId)
            ->active()
            ->latest('effective_date')
            ->first();

        return $price ? (float) $price->price_per_litre : null;
    }

    public static function getPriceOnDate(int $fuelId, $date): ?float
    {
        $price = self::forFuel($fuelId)
            ->effectiveOn($date)
            ->first();

        return $price ? (float) $price->price_per_litre : null;
    }
}
