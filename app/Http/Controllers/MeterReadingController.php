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

        // Load fuel tank levels
        $fuels = Fuel::withCount('pumps')->get();

        return view('dashboard.fuel.meter_readings.index', compact('meterReadings', 'todayReadings', 'unverifiedReadings', 'fuels'));
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

        // Calculate dispensed liters
        $totalDispensed = $validated['closing_reading'] - $validated['opening_reading'];
        $totalAmount = $totalDispensed * $validated['price_per_liter'];

        \DB::transaction(function () use ($validated, $totalDispensed, $totalAmount) {
            // Check if enough fuel stock is available
            $fuel = Fuel::findOrFail($validated['fuel_id']);
            if ($fuel->stock_litres < $totalDispensed) {
                throw new \Exception("Insufficient fuel stock. Available: {$fuel->stock_litres}L, Required: {$totalDispensed}L");
            }

            // Create meter reading
            $meterReading = MeterReading::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);

            // Deduct fuel from stock
            $fuel->decrement('stock_litres', $totalDispensed);

            // Create income record for fuel sales
            $incomeAccount = \App\Models\Account::where('code', '3001')->first();
            if ($incomeAccount) {
                \App\Models\Income::create([
                    'account_id' => $incomeAccount->id,
                    'user_id' => Auth::id(),
                    'amount' => $totalAmount,
                    'description' => "Fuel Sales - {$fuel->name} - Pump #{$validated['pump_id']} - {$validated['shift']} shift",
                    'date' => $validated['reading_date'],
                    'reference' => 'meter_reading:'.$meterReading->id,
                ]);

                // Update account balance
                $incomeAccount->increment('current_balance', $totalAmount);
            }
        });

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

        // Calculate old and new dispensed amounts
        $oldTotalDispensed = $meterReading->total_dispensed;
        $oldTotalAmount = $meterReading->total_amount;
        $newTotalDispensed = $validated['closing_reading'] - $validated['opening_reading'];
        $newTotalAmount = $newTotalDispensed * $validated['price_per_liter'];

        \DB::transaction(function () use ($validated, $meterReading, $oldTotalDispensed, $oldTotalAmount, $newTotalDispensed, $newTotalAmount) {
            $fuel = Fuel::findOrFail($validated['fuel_id']);

            // Revert old stock deduction
            $fuel->increment('stock_litres', $oldTotalDispensed);

            // Check if enough fuel stock for new amount
            if ($fuel->stock_litres < $newTotalDispensed) {
                throw new \Exception("Insufficient fuel stock. Available: {$fuel->stock_litres}L, Required: {$newTotalDispensed}L");
            }

            // Apply new stock deduction
            $fuel->decrement('stock_litres', $newTotalDispensed);

            // Update income record
            $reference = 'meter_reading:'.$meterReading->id;
            $income = \App\Models\Income::where('reference', $reference)->first();
            if ($income) {
                $account = $income->account;

                // Revert old income
                $account->decrement('current_balance', $oldTotalAmount);

                // Update income record
                $income->update([
                    'amount' => $newTotalAmount,
                    'description' => "Fuel Sales - {$fuel->name} - Pump #{$validated['pump_id']} - {$validated['shift']} shift",
                    'date' => $validated['reading_date'],
                ]);

                // Apply new income
                $account->increment('current_balance', $newTotalAmount);
            }

            // Update meter reading
            $meterReading->update($validated);
        });

        return redirect()->route('fuel.meter-readings.index')->with('success', 'Meter reading updated successfully.');
    }

    /**
     * Remove the specified meter reading from storage.
     */
    public function destroy(MeterReading $meterReading)
    {
        \DB::transaction(function () use ($meterReading) {
            // Revert fuel stock deduction
            $fuel = Fuel::findOrFail($meterReading->fuel_id);
            $fuel->increment('stock_litres', $meterReading->total_dispensed);

            // Delete income record
            $reference = 'meter_reading:'.$meterReading->id;
            $income = \App\Models\Income::where('reference', $reference)->first();
            if ($income) {
                $account = $income->account;

                // Revert income from account balance
                $account->decrement('current_balance', $income->amount);

                $income->delete();
            }

            $meterReading->delete();
        });

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
