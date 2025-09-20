<?php

namespace App\Http\Controllers;

use App\Models\FuelAdjustment;
use App\Models\MeterReading;
use App\Models\Pump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FuelAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = FuelAdjustment::with(['pump', 'fuel', 'user'])
            ->orderByDesc('adjusted_at')
            ->paginate(20);

        $relatedMeterReadings = MeterReading::with(['pump', 'fuel', 'user'])
            ->whereIn('pump_id', $adjustments->pluck('pump_id')->unique())
            ->latest('reading_date')
            ->limit(10)
            ->get();

        $adjustmentSummary = FuelAdjustment::selectRaw('
            type,
            COUNT(*) as count,
            SUM(liters) as total_liters
        ')
            ->whereDate('adjusted_at', today())
            ->groupBy('type')
            ->get();

        return view('dashboard.fuel_adjustments.index', compact('adjustments', 'relatedMeterReadings', 'adjustmentSummary'));
    }

    public function create()
    {
        $pumps = Pump::with('fuel')->get();

        return view('dashboard.fuel_adjustments.create', compact('pumps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pump_id' => ['required', Rule::exists('pumps', 'id')],
            'fuel_id' => ['required', Rule::exists('fuels', 'id')],
            'liters' => 'required|numeric|min:0',
            'type' => ['required', Rule::in(['gain', 'loss'])],
            'reason' => 'nullable|string|max:1000',
            'adjusted_at' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            FuelAdjustment::create([
                'pump_id' => $request->pump_id,
                'fuel_id' => $request->fuel_id,
                'user_id' => Auth::id(),
                'liters' => $request->liters,
                'type' => $request->type,
                'reason' => $request->reason,
                'adjusted_at' => $request->adjusted_at,
            ]);

            // Note: Fuel adjustments don't affect meter readings
            // Meter readings track fuel dispensed, not stock adjustments
        });

        return redirect()->route('fuel-adjustments.index')->with('success', 'Fuel adjustment recorded.');
    }

    public function update(Request $request, FuelAdjustment $fuelAdjustment)
    {
        $request->validate([
            'liters' => 'required|numeric|min:0',
            'type' => ['required', Rule::in(['gain', 'loss'])],
            'reason' => 'nullable|string|max:1000',
            'adjusted_at' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $fuelAdjustment) {
            // Note: Fuel adjustments don't affect meter readings
            // Meter readings track fuel dispensed, not stock adjustments

            $fuelAdjustment->update([
                'liters' => $request->liters,
                'type' => $request->type,
                'reason' => $request->reason,
                'adjusted_at' => $request->adjusted_at,
            ]);
        });

        return redirect()->route('fuel-adjustments.index')->with('success', 'Fuel adjustment updated.');
    }

    public function destroy(FuelAdjustment $fuelAdjustment)
    {
        DB::transaction(function () use ($fuelAdjustment) {
            // Note: Fuel adjustments don't affect meter readings
            // Meter readings track fuel dispensed, not stock adjustments

            $fuelAdjustment->delete();
        });

        return redirect()->route('fuel-adjustments.index')->with('success', 'Fuel adjustment deleted.');
    }

    public function show(FuelAdjustment $fuelAdjustment)
    {
        $fuelAdjustment->load(['pump.fuel', 'user']);

        $relatedMeterReadings = MeterReading::where('pump_id', $fuelAdjustment->pump_id)
            ->whereDate('reading_date', $fuelAdjustment->adjusted_at)
            ->with(['user', 'verifiedBy'])
            ->get();

        $meterReadingsAroundDate = MeterReading::where('pump_id', $fuelAdjustment->pump_id)
            ->whereBetween('reading_date', [
                $fuelAdjustment->adjusted_at->subDays(3),
                $fuelAdjustment->adjusted_at->addDays(3),
            ])
            ->with(['user', 'verifiedBy'])
            ->orderBy('reading_date')
            ->get();

        return view('dashboard.fuel_adjustments.show', compact('fuelAdjustment', 'relatedMeterReadings', 'meterReadingsAroundDate'));
    }

    public function edit(FuelAdjustment $fuelAdjustment)
    {
        $pumps = Pump::with('fuel')->get();

        return view('dashboard.fuel_adjustments.edit', compact('fuelAdjustment', 'pumps'));
    }
}
