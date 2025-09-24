<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\MeterReading;
use App\Models\Pump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeterReadingController extends Controller
{
    /**
     * Display a listing of the meter readings.
     */
    public function index()
    {
        $meterReadings = MeterReading::with(['pump', 'fuel', 'user', 'verifiedBy'])
            ->latest('reading_date')
            ->paginate(20);

        $todayReadings = MeterReading::today()->count();
        $unverifiedReadings = MeterReading::unverified()->count();

        return view('dashboard.fuel.meter_readings.index', compact('meterReadings', 'todayReadings', 'unverifiedReadings'));
    }

    /**
     * Show the form for creating a new meter reading.
     */
    public function create()
    {
        $pumps = Pump::with('fuel')->where('is_active', true)->get();
        $fuels = Fuel::all();

        // Prepare default opening readings for each pump
        $defaultOpenings = [];
        foreach ($pumps as $pump) {
            $defaultOpenings[$pump->id] = MeterReading::lastClosingReading($pump->id);
        }

        return view('dashboard.fuel.meter_readings.create', compact('pumps', 'fuels', 'defaultOpenings'));
    }

    /**
     * Store a newly created meter reading in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pump_id' => 'required|exists:pumps,id',
            'fuel_id' => 'required|exists:fuels,id',
            'opening_reading' => 'required|numeric|min:0',
            'closing_reading' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'reading_date' => 'required|date',
            'reading_time' => 'required',
            'shift' => 'required|in:morning,afternoon,evening,night',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['closing_reading'] < $validated['opening_reading']) {
            return back()->withErrors(['closing_reading' => 'Closing reading must be greater than opening reading.'])->withInput();
        }

        MeterReading::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading recorded successfully.');
    }

    /**
     * Display the specified meter reading.
     */
    public function show(MeterReading $meterReading)
    {
        $meterReading->load(['pump.fuel', 'user', 'verifiedBy']);
        return view('dashboard.fuel.meter_readings.show', compact('meterReading'));
    }

    /**
     * Show the form for editing the specified meter reading.
     */
    public function edit(MeterReading $meterReading)
    {
        $pumps = Pump::with('fuel')->where('is_active', true)->get();
        $fuels = Fuel::all();

        return view('dashboard.fuel.meter_readings.edit', compact('meterReading', 'pumps', 'fuels'));
    }

    /**
     * Update the specified meter reading in storage.
     */
    public function update(Request $request, MeterReading $meterReading)
    {
        $validated = $request->validate([
            'pump_id' => 'required|exists:pumps,id',
            'fuel_id' => 'required|exists:fuels,id',
            'opening_reading' => 'required|numeric|min:0',
            'closing_reading' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'reading_date' => 'required|date',
            'reading_time' => 'required',
            'shift' => 'required|in:morning,afternoon,evening,night',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['closing_reading'] < $validated['opening_reading']) {
            return back()->withErrors(['closing_reading' => 'Closing reading must be greater than opening reading.'])->withInput();
        }

        $meterReading->update($validated);

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading updated successfully.');
    }

    /**
     * Remove the specified meter reading from storage.
     */
    public function destroy(MeterReading $meterReading)
    {
        $meterReading->delete();
        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading deleted successfully.');
    }

    /**
     * Verify a meter reading.
     */
    public function verify(MeterReading $meterReading)
    {
        $meterReading->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return back()->with('success', 'Meter reading verified successfully.');
    }
}
