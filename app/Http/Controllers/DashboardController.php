<?php

namespace App\Http\Controllers;

use App\Models\Pump;

class DashboardController extends Controller
{
    public function index()
    {
        // Load pumps with their associated fuel and current meter reading
        $pumps = Pump::with([
            'fuel:id,name',
            'currentMeterReading:id,pump_id,current_meter_reading,previous_meter_reading',
        ])->get();

        return view('dashboard', compact('pumps'));
    }
}
