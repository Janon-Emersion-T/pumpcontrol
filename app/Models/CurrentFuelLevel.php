<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentFuelLevel extends Model
{
    protected $fillable = ['pump_id', 'current_fuel'];

    public function pump()
    {
        return $this->belongsTo(Pump::class);
    }
}
