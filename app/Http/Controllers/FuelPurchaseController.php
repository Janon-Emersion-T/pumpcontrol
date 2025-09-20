<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Fuel;
use App\Models\FuelPurchase;
use App\Models\Pump;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FuelPurchaseController extends Controller
{
    public function index()
    {
        $purchases = FuelPurchase::with(['pump', 'fuel', 'supplier'])->orderByDesc('purchase_date')->paginate(20);

        return view('dashboard.fuel_purchases.index', compact('purchases'));
    }

    public function create()
    {
        $pumps = Pump::with('fuel')->get();
        $suppliers = Supplier::all();

        return view('dashboard.fuel_purchases.create', compact('pumps', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pump_id' => ['required', Rule::exists('pumps', 'id')],
            'fuel_id' => ['required', Rule::exists('fuels', 'id')],
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')],
            'liters' => 'required|numeric|min:0.01',
            'price_per_liter' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            $userId = auth()->id();
            $totalCost = $request->liters * $request->price_per_liter;
            $account = Account::where('code', '2001')->firstOrFail();

            $purchase = FuelPurchase::create([
                'pump_id' => $request->pump_id,
                'fuel_id' => $request->fuel_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => $userId,
                'liters' => $request->liters,
                'price_per_liter' => $request->price_per_liter,
                'total_cost' => $totalCost,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
            ]);

            Expense::create([
                'account_id' => $account->id,
                'user_id' => $userId,
                'amount' => $totalCost,
                'description' => "Fuel Purchase - Pump #{$request->pump_id} on {$request->purchase_date}",
                'date' => $request->purchase_date,
                'reference' => 'fuel_purchase:'.$purchase->id,
            ]);

            if ($account->type === 'Expense') {
                $account->decrement('current_balance', $totalCost);
            }

            // Note: Fuel purchases don't affect meter readings
            // Meter readings track fuel dispensed, not fuel received
        });

        return redirect()->route('fuel-purchases.index')->with('success', 'Fuel purchase and expense recorded.');
    }

    public function show(FuelPurchase $fuelPurchase)
    {
        return view('dashboard.fuel_purchases.show', compact('fuelPurchase'));
    }

    public function edit(FuelPurchase $fuelPurchase)
    {
        $pumps = Pump::with('fuel')->get();
        $suppliers = Supplier::all();

        return view('dashboard.fuel_purchases.edit', compact('fuelPurchase', 'pumps', 'suppliers'));
    }

    public function update(Request $request, FuelPurchase $fuelPurchase)
    {
        $request->validate([
            'liters' => 'required|numeric|min:0.01',
            'price_per_liter' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $fuelPurchase) {
            $userId = auth()->id();
            $oldLiters = $fuelPurchase->liters;
            $oldAmount = $fuelPurchase->total_cost;
            $newAmount = $request->liters * $request->price_per_liter;

            $account = Account::where('code', '2001')->firstOrFail();
            $reference = 'fuel_purchase:'.$fuelPurchase->id;

            $expense = Expense::where('reference', $reference)->first();
            if ($expense) {
                $expense->update([
                    'amount' => $newAmount,
                    'description' => "Fuel Purchase - Pump #{$fuelPurchase->pump_id} on {$request->purchase_date}",
                    'date' => $request->purchase_date,
                ]);
            }

            if ($account->type === 'Expense') {
                $account->increment('current_balance', $oldAmount);
                $account->decrement('current_balance', $newAmount);
            }

            $fuelPurchase->update([
                'liters' => $request->liters,
                'price_per_liter' => $request->price_per_liter,
                'total_cost' => $newAmount,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
            ]);

            // Note: Fuel purchases don't affect meter readings
            // Meter readings track fuel dispensed, not fuel received
        });

        return redirect()->route('fuel-purchases.index')->with('success', 'Fuel purchase and accounting updated.');
    }

    public function destroy(FuelPurchase $fuelPurchase)
    {
        DB::transaction(function () use ($fuelPurchase) {
            $account = Account::where('code', '2001')->firstOrFail();
            $reference = 'fuel_purchase:'.$fuelPurchase->id;

            $expense = Expense::where('reference', $reference)->first();
            if ($expense) {
                $expense->delete();

                if ($account->type === 'Expense') {
                    $account->increment('current_balance', $fuelPurchase->total_cost);
                }
            }

            // Note: Fuel purchases don't affect meter readings
            // Meter readings track fuel dispensed, not fuel received

            $fuelPurchase->delete();
        });

        return redirect()->route('fuel-purchases.index')->with('success', 'Fuel purchase and expense deleted.');
    }
}
