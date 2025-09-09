<?php

namespace App\Http\Controllers;

use App\Models\Pump;

class DashboardController extends Controller
{
    public function index()
    {
        // Load pumps with their associated fuel and current fuel level
        $pumps = Pump::with([
            'fuel:id,name',
            'currentFuel:id,pump_id,current_fuel'
        ])->get();

        return view('dashboard', compact('pumps'));
    }
}
