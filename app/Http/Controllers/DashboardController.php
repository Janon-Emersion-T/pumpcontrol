<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\Fuel;

class DashboardController extends Controller
{
    public function index()
    {
        // Load pumps with their associated fuel and current meter reading
        $pumps = Pump::with([
            'fuel:id,name',
            'currentMeterReading:id,pump_id,current_meter_reading,previous_meter_reading',
        ])->get();

        // Load all fuels with their stock levels
        $fuels = Fuel::withCount('pumps')->get();

        return view('dashboard', compact('pumps', 'fuels'));
    }
}
