<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\FuelPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelPriceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $priceHistory = FuelPriceHistory::with(['fuel', 'user'])
            ->latest('effective_date')
            ->latest('created_at')
            ->paginate(20);

        return view('dashboard.fuel-price-history.index', compact('priceHistory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fuels = Fuel::all();
        return view('dashboard.fuel-price-history.create', compact('fuels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fuel_id' => 'required|exists:fuels,id',
            'price_per_litre' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Deactivate all previous prices for this fuel
            FuelPriceHistory::where('fuel_id', $validated['fuel_id'])
                ->update(['is_active' => false]);

            // Create new price history record
            $priceHistory = FuelPriceHistory::create([
                'fuel_id' => $validated['fuel_id'],
                'price_per_litre' => $validated['price_per_litre'],
                'effective_date' => $validated['effective_date'],
                'user_id' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
                'is_active' => true,
            ]);

            // Update the fuel table with the current price
            $fuel = Fuel::find($validated['fuel_id']);
            $fuel->update(['price_per_litre' => $validated['price_per_litre']]);
        });

        return redirect()
            ->route('fuel-price-history.index')
            ->with('success', 'Fuel price updated successfully. New price is now active.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FuelPriceHistory $fuelPriceHistory)
    {
        $fuelPriceHistory->load(['fuel', 'user']);
        return view('dashboard.fuel-price-history.show', compact('fuelPriceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FuelPriceHistory $fuelPriceHistory)
    {
        $fuels = Fuel::all();
        return view('dashboard.fuel-price-history.edit', compact('fuelPriceHistory', 'fuels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FuelPriceHistory $fuelPriceHistory)
    {
        $validated = $request->validate([
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $fuelPriceHistory->update($validated);

        return redirect()
            ->route('fuel-price-history.index')
            ->with('success', 'Price history record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FuelPriceHistory $fuelPriceHistory)
    {
        // Prevent deletion of active price
        if ($fuelPriceHistory->is_active) {
            return redirect()
                ->route('fuel-price-history.index')
                ->with('error', 'Cannot delete the active price. Please create a new price first.');
        }

        $fuelPriceHistory->delete();

        return redirect()
            ->route('fuel-price-history.index')
            ->with('success', 'Price history record deleted successfully.');
    }
}
