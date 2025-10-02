<?php

namespace App\Observers;

use App\Models\FuelAdjustment;

class FuelAdjustmentObserver
{
    /**
     * Handle the FuelAdjustment "created" event.
     */
    public function created(FuelAdjustment $fuelAdjustment): void
    {
        //
    }

    /**
     * Handle the FuelAdjustment "updated" event.
     */
    public function updated(FuelAdjustment $fuelAdjustment): void
    {
        //
    }

    /**
     * Handle the FuelAdjustment "deleted" event.
     */
    public function deleted(FuelAdjustment $fuelAdjustment): void
    {
        //
    }

    /**
     * Handle the FuelAdjustment "restored" event.
     */
    public function restored(FuelAdjustment $fuelAdjustment): void
    {
        //
    }

    /**
     * Handle the FuelAdjustment "force deleted" event.
     */
    public function forceDeleted(FuelAdjustment $fuelAdjustment): void
    {
        //
    }
}
