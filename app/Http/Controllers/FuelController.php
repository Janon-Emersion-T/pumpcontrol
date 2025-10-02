<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\MeterReading;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function index()
    {
        $fuels = Fuel::with(['meterReadings' => function ($query) {
            $query->latest('reading_date')->limit(5);
        }])->latest()->paginate(10);

        $todayMeterReadings = MeterReading::with(['fuel', 'pump', 'user'])
            ->whereDate('reading_date', today())
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.fuel.index', compact('fuels', 'todayMeterReadings'));
    }

    public function create()
    {
        return view('dashboard.fuel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_litre' => 'required|numeric',
            'stock_litres' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Fuel::create($request->all());

        return redirect()->route('dashboard.fuel.index')->with('success', 'Fuel added successfully.');
    }

    public function show(Fuel $fuel)
    {
        $fuel->load(['meterReadings' => function ($query) {
            $query->with(['pump', 'user', 'verifiedBy'])
                ->latest('reading_date')
                ->limit(20);
        }]);

        $recentMeterReadings = $fuel->meterReadings;
        $totalDispensed = $fuel->meterReadings()
            ->whereDate('reading_date', today())
            ->sum('total_dispensed');

        return view('dashboard.fuel.show', compact('fuel', 'recentMeterReadings', 'totalDispensed'));
    }

    public function edit(Fuel $fuel)
    {
        return view('dashboard.fuel.edit', compact('fuel'));
    }

    public function update(Request $request, Fuel $fuel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_litre' => 'required|numeric',
            'stock_litres' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $fuel->update($request->all());

        return redirect()->route('dashboard.fuel.index')->with('success', 'Fuel updated successfully.');
    }

    public function destroy(Fuel $fuel)
    {
        $fuel->delete();

        return redirect()->route('fuel.index')->with('success', 'Fuel deleted successfully.');
    }
}
