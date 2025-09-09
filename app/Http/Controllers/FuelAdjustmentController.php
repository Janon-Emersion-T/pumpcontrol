<?php

namespace App\Http\Controllers;

use App\Models\FuelAdjustment;
use App\Models\Pump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FuelAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = FuelAdjustment::with(['pump', 'fuel'])->orderByDesc('adjusted_at')->paginate(20);
        return view('dashboard.fuel_adjustments.index', compact('adjustments'));
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

            $pump = Pump::with('currentFuel')->findOrFail($request->pump_id);
            $adjustedLiters = $request->type === 'gain' ? $request->liters : -$request->liters;

            if ($pump->currentFuel) {
                $pump->currentFuel()->update([
                    'current_fuel' => DB::raw("current_fuel + ($adjustedLiters)")
                ]);
            } else {
                $pump->currentFuel()->create([
                    'current_fuel' => $adjustedLiters
                ]);
            }
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
            $pump = $fuelAdjustment->pump;

            $previousAdjustment = $fuelAdjustment->type === 'gain' ? $fuelAdjustment->liters : -$fuelAdjustment->liters;
            $newAdjustment = $request->type === 'gain' ? $request->liters : -$request->liters;
            $netChange = $newAdjustment - $previousAdjustment;

            if ($pump->currentFuel) {
                $pump->currentFuel()->update([
                    'current_fuel' => DB::raw("current_fuel + ($netChange)")
                ]);
            } else {
                $pump->currentFuel()->create([
                    'current_fuel' => $netChange
                ]);
            }

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
            $pump = $fuelAdjustment->pump;
            $revertLiters = $fuelAdjustment->type === 'gain' ? -$fuelAdjustment->liters : $fuelAdjustment->liters;

            if ($pump->currentFuel) {
                $pump->currentFuel()->update([
                    'current_fuel' => DB::raw("current_fuel + ($revertLiters)")
                ]);
            } else {
                $pump->currentFuel()->create([
                    'current_fuel' => $revertLiters
                ]);
            }

            $fuelAdjustment->delete();
        });

        return redirect()->route('fuel-adjustments.index')->with('success', 'Fuel adjustment deleted.');
    }

    public function show(FuelAdjustment $fuelAdjustment)
    {
        return view('dashboard.fuel_adjustments.show', compact('fuelAdjustment'));
    }

    public function edit(FuelAdjustment $fuelAdjustment)
    {
        $pumps = Pump::with('fuel')->get();
        return view('dashboard.fuel_adjustments.edit', compact('fuelAdjustment', 'pumps'));
    }
}
