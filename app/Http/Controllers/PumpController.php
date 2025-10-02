<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\MeterReading;
use App\Models\Pump;
use Illuminate\Http\Request;

class PumpController extends Controller
{
    public function index()
    {
        $pumps = Pump::with(['fuel', 'latestMeterReading', 'meterReadings' => function ($query) {
            $query->whereDate('reading_date', today())->latest();
        }])->latest()->paginate(10);

        $todayReadingsCount = MeterReading::whereDate('reading_date', today())->count();
        $unverifiedReadings = MeterReading::unverified()->count();

        return view('dashboard.pump.index', compact('pumps', 'todayReadingsCount', 'unverifiedReadings'));
    }

    public function create()
    {
        $fuels = Fuel::all();

        return view('dashboard.pump.create', compact('fuels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fuel_id' => 'required|exists:fuels,id',
            'is_active' => 'boolean',
        ]);

        // Default is_active to true if not checked
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Pump::create($data);

        return redirect()->route('pump.index')->with('success', 'Pump added successfully.');
    }

    public function show(Pump $pump)
    {
        $pump->load(['fuel', 'meterReadings' => function ($query) {
            $query->with(['user', 'verifiedBy'])
                ->latest('reading_date')
                ->limit(30);
        }]);

        $todayMeterReadings = $pump->meterReadings()
            ->whereDate('reading_date', today())
            ->with(['user', 'verifiedBy'])
            ->get();

        $totalDispensedToday = $todayMeterReadings->sum('total_dispensed');
        $totalAmountToday = $todayMeterReadings->sum('total_amount');

        return view('dashboard.pump.show', compact('pump', 'todayMeterReadings', 'totalDispensedToday', 'totalAmountToday'));
    }

    public function edit(Pump $pump)
    {
        $fuels = Fuel::all();

        return view('dashboard.pump.edit', compact('pump', 'fuels'));
    }

    public function update(Request $request, Pump $pump)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fuel_id' => 'required|exists:fuels,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $pump->update($data);

        return redirect()->route('pump.index')->with('success', 'Pump updated successfully.');
    }

    public function destroy(Pump $pump)
    {
        $pump->delete();

        return redirect()->route('pump.index')->with('success', 'Pump deleted successfully.');
    }
}
