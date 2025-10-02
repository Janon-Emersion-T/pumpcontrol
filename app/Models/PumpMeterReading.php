<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpMeterReading extends Model
{
    protected $fillable = [
        'pump_id',
        'current_meter_reading',
        'previous_meter_reading',
    ];

    protected function casts(): array
    {
        return [
            'current_meter_reading' => 'decimal:2',
            'previous_meter_reading' => 'decimal:2',
        ];
    }

    public function pump()
    {
        return $this->belongsTo(Pump::class);
    }

    public function getLitersSoldAttribute(): float
    {
        if ($this->previous_meter_reading === null) {
            return 0;
        }

        return $this->current_meter_reading - $this->previous_meter_reading;
    }
}
