<?php

namespace App\Observers;

use App\Models\MeterReading;

class MeterReadingObserver
{
    /**
     * Handle the MeterReading "created" event.
     */
    public function created(MeterReading $meterReading): void
    {
        //
    }

    /**
     * Handle the MeterReading "updated" event.
     */
    public function updated(MeterReading $meterReading): void
    {
        //
    }

    /**
     * Handle the MeterReading "deleted" event.
     */
    public function deleted(MeterReading $meterReading): void
    {
        //
    }

    /**
     * Handle the MeterReading "restored" event.
     */
    public function restored(MeterReading $meterReading): void
    {
        //
    }

    /**
     * Handle the MeterReading "force deleted" event.
     */
    public function forceDeleted(MeterReading $meterReading): void
    {
        //
    }
}
