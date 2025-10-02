<?php

namespace App\Observers;

use App\Models\FuelPurchase;

class FuelPurchaseObserver
{
    /**
     * Handle the FuelPurchase "created" event.
     */
    public function created(FuelPurchase $fuelPurchase): void
    {
        //
    }

    /**
     * Handle the FuelPurchase "updated" event.
     */
    public function updated(FuelPurchase $fuelPurchase): void
    {
        //
    }

    /**
     * Handle the FuelPurchase "deleted" event.
     */
    public function deleted(FuelPurchase $fuelPurchase): void
    {
        //
    }

    /**
     * Handle the FuelPurchase "restored" event.
     */
    public function restored(FuelPurchase $fuelPurchase): void
    {
        //
    }

    /**
     * Handle the FuelPurchase "force deleted" event.
     */
    public function forceDeleted(FuelPurchase $fuelPurchase): void
    {
        //
    }
}
