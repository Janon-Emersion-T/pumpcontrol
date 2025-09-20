<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\MeterReading;
use App\Models\Pump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeterReadingController extends Controller
{
    public function index()
    {
        $meterReadings = MeterReading::with(['pump', 'fuel', 'user', 'verifiedBy'])
            ->latest('reading_date')
            ->paginate(20);

        $todayReadings = MeterReading::whereDate('reading_date', today())->count();
        $unverifiedReadings = MeterReading::unverified()->count();

        return view('dashboard.fuel.meter_readings.index', compact('meterReadings', 'todayReadings', 'unverifiedReadings'));
    }

    public function create()
    {
        $pumps = Pump::with('fuel')->where('is_active', true)->get();
        $fuels = Fuel::all();

        return view('dashboard.fuel.meter_readings.create', compact('pumps', 'fuels'));
    }

    public function store(Request $request)
    {
        $request->validate([
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

        $totalDispensed = $request->closing_reading - $request->opening_reading;
        $totalAmount = $totalDispensed * $request->price_per_liter;

        if ($totalDispensed < 0) {
            return back()->withErrors(['closing_reading' => 'Closing reading must be greater than opening reading.']);
        }

        MeterReading::create([
            'pump_id' => $request->pump_id,
            'fuel_id' => $request->fuel_id,
            'user_id' => Auth::id(),
            'opening_reading' => $request->opening_reading,
            'closing_reading' => $request->closing_reading,
            'total_dispensed' => $totalDispensed,
            'price_per_liter' => $request->price_per_liter,
            'total_amount' => $totalAmount,
            'reading_date' => $request->reading_date,
            'reading_time' => $request->reading_time,
            'shift' => $request->shift,
            'notes' => $request->notes,
        ]);

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading recorded successfully.');
    }

    public function show(MeterReading $meterReading)
    {
        $meterReading->load(['pump.fuel', 'user', 'verifiedBy']);

        return view('dashboard.fuel.meter_readings.show', compact('meterReading'));
    }

    public function edit(MeterReading $meterReading)
    {
        $pumps = Pump::with('fuel')->where('is_active', true)->get();
        $fuels = Fuel::all();

        return view('dashboard.fuel.meter_readings.edit', compact('meterReading', 'pumps', 'fuels'));
    }

    public function update(Request $request, MeterReading $meterReading)
    {
        $request->validate([
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

        $totalDispensed = $request->closing_reading - $request->opening_reading;
        $totalAmount = $totalDispensed * $request->price_per_liter;

        if ($totalDispensed < 0) {
            return back()->withErrors(['closing_reading' => 'Closing reading must be greater than opening reading.']);
        }

        $meterReading->update([
            'pump_id' => $request->pump_id,
            'fuel_id' => $request->fuel_id,
            'opening_reading' => $request->opening_reading,
            'closing_reading' => $request->closing_reading,
            'total_dispensed' => $totalDispensed,
            'price_per_liter' => $request->price_per_liter,
            'total_amount' => $totalAmount,
            'reading_date' => $request->reading_date,
            'reading_time' => $request->reading_time,
            'shift' => $request->shift,
            'notes' => $request->notes,
        ]);

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading updated successfully.');
    }

    public function destroy(MeterReading $meterReading)
    {
        $meterReading->delete();

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading deleted successfully.');
    }

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
