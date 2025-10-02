<?php

namespace App\Services;

use App\Models\Fuel;
use App\Models\FuelPurchase;
use App\Models\FuelAdjustment;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class FuelService
{
    /**
     * Process a fuel purchase transaction
     */
    public function processPurchase(array $data): FuelPurchase
    {
        return DB::transaction(function () use ($data) {
            $totalCost = $data['liters'] * $data['price_per_liter'];

            // Create purchase record
            $purchase = FuelPurchase::create([
                'pump_id' => $data['pump_id'],
                'fuel_id' => $data['fuel_id'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => $data['user_id'],
                'liters' => $data['liters'],
                'price_per_liter' => $data['price_per_liter'],
                'total_cost' => $totalCost,
                'purchase_date' => $data['purchase_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Create expense record
            $expenseAccount = Account::where('code', '2001')->firstOrFail();
            Expense::create([
                'account_id' => $expenseAccount->id,
                'user_id' => $data['user_id'],
                'amount' => $totalCost,
                'description' => "Fuel Purchase - Pump #{$data['pump_id']} on {$data['purchase_date']}",
                'date' => $data['purchase_date'],
                'reference' => 'fuel_purchase:' . $purchase->id,
            ]);

            // Update account balance
            if ($expenseAccount->type === 'Expense') {
                $expenseAccount->decrement('current_balance', $totalCost);
            }

            // Update fuel stock
            $fuel = Fuel::findOrFail($data['fuel_id']);
            $fuel->increment('stock_litres', $data['liters']);

            return $purchase->fresh(['pump', 'fuel', 'supplier']);
        });
    }

    /**
     * Update a fuel purchase
     */
    public function updatePurchase(FuelPurchase $purchase, array $data): FuelPurchase
    {
        return DB::transaction(function () use ($purchase, $data) {
            $oldLiters = $purchase->liters;
            $oldAmount = $purchase->total_cost;
            $newLiters = $data['liters'];
            $newAmount = $newLiters * $data['price_per_liter'];

            // Update expense
            $expenseAccount = Account::where('code', '2001')->firstOrFail();
            $reference = 'fuel_purchase:' . $purchase->id;
            $expense = Expense::where('reference', $reference)->first();

            if ($expense) {
                $expense->update([
                    'amount' => $newAmount,
                    'description' => "Fuel Purchase - Pump #{$purchase->pump_id} on {$data['purchase_date']}",
                    'date' => $data['purchase_date'],
                ]);
            }

            // Update account balance
            if ($expenseAccount->type === 'Expense') {
                $expenseAccount->increment('current_balance', $oldAmount);
                $expenseAccount->decrement('current_balance', $newAmount);
            }

            // Update fuel stock
            $fuel = Fuel::findOrFail($purchase->fuel_id);
            $litersDifference = $newLiters - $oldLiters;
            if ($litersDifference > 0) {
                $fuel->increment('stock_litres', $litersDifference);
            } elseif ($litersDifference < 0) {
                $fuel->decrement('stock_litres', abs($litersDifference));
            }

            // Update purchase
            $purchase->update([
                'liters' => $newLiters,
                'price_per_liter' => $data['price_per_liter'],
                'total_cost' => $newAmount,
                'purchase_date' => $data['purchase_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            return $purchase->fresh(['pump', 'fuel', 'supplier']);
        });
    }

    /**
     * Delete a fuel purchase
     */
    public function deletePurchase(FuelPurchase $purchase): bool
    {
        return DB::transaction(function () use ($purchase) {
            $expenseAccount = Account::where('code', '2001')->firstOrFail();
            $reference = 'fuel_purchase:' . $purchase->id;

            // Delete expense
            $expense = Expense::where('reference', $reference)->first();
            if ($expense) {
                $expense->delete();

                if ($expenseAccount->type === 'Expense') {
                    $expenseAccount->increment('current_balance', $purchase->total_cost);
                }
            }

            // Revert fuel stock
            $fuel = Fuel::findOrFail($purchase->fuel_id);
            $fuel->decrement('stock_litres', $purchase->liters);

            return $purchase->delete();
        });
    }

    /**
     * Process a fuel adjustment
     */
    public function processAdjustment(array $data): FuelAdjustment
    {
        return DB::transaction(function () use ($data) {
            $fuel = Fuel::findOrFail($data['fuel_id']);

            // Validate loss doesn't exceed stock
            if ($data['type'] === 'loss' && $fuel->stock_litres < $data['liters']) {
                throw new \Exception("Cannot record loss adjustment. Available stock: {$fuel->stock_litres}L, Loss amount: {$data['liters']}L");
            }

            // Create adjustment
            $adjustment = FuelAdjustment::create([
                'pump_id' => $data['pump_id'],
                'fuel_id' => $data['fuel_id'],
                'user_id' => $data['user_id'],
                'liters' => $data['liters'],
                'type' => $data['type'],
                'reason' => $data['reason'] ?? null,
                'adjusted_at' => $data['adjusted_at'],
            ]);

            // Update stock
            if ($data['type'] === 'gain') {
                $fuel->increment('stock_litres', $data['liters']);
            } else {
                $fuel->decrement('stock_litres', $data['liters']);
            }

            return $adjustment->fresh(['pump', 'fuel', 'user']);
        });
    }

    /**
     * Update a fuel adjustment
     */
    public function updateAdjustment(FuelAdjustment $adjustment, array $data): FuelAdjustment
    {
        return DB::transaction(function () use ($adjustment, $data) {
            $fuel = Fuel::findOrFail($adjustment->fuel_id);
            $oldLiters = $adjustment->liters;
            $oldType = $adjustment->type;
            $newLiters = $data['liters'];
            $newType = $data['type'];

            // Revert old adjustment
            if ($oldType === 'gain') {
                $fuel->decrement('stock_litres', $oldLiters);
            } else {
                $fuel->increment('stock_litres', $oldLiters);
            }

            // Validate new loss
            if ($newType === 'loss' && $fuel->stock_litres < $newLiters) {
                throw new \Exception("Cannot update adjustment. Available stock: {$fuel->stock_litres}L, Loss amount: {$newLiters}L");
            }

            // Apply new adjustment
            if ($newType === 'gain') {
                $fuel->increment('stock_litres', $newLiters);
            } else {
                $fuel->decrement('stock_litres', $newLiters);
            }

            // Update record
            $adjustment->update([
                'liters' => $newLiters,
                'type' => $newType,
                'reason' => $data['reason'] ?? null,
                'adjusted_at' => $data['adjusted_at'],
            ]);

            return $adjustment->fresh(['pump', 'fuel', 'user']);
        });
    }

    /**
     * Delete a fuel adjustment
     */
    public function deleteAdjustment(FuelAdjustment $adjustment): bool
    {
        return DB::transaction(function () use ($adjustment) {
            $fuel = Fuel::findOrFail($adjustment->fuel_id);

            // Revert adjustment
            if ($adjustment->type === 'gain') {
                $fuel->decrement('stock_litres', $adjustment->liters);
            } else {
                $fuel->increment('stock_litres', $adjustment->liters);
            }

            return $adjustment->delete();
        });
    }

    /**
     * Get current stock level for a fuel
     */
    public function getCurrentStock(int $fuelId): float
    {
        $fuel = Fuel::findOrFail($fuelId);
        return $fuel->stock_litres;
    }

    /**
     * Check if sufficient stock is available
     */
    public function hasSufficientStock(int $fuelId, float $requiredLiters): bool
    {
        return $this->getCurrentStock($fuelId) >= $requiredLiters;
    }

    /**
     * Get low stock fuels (below threshold)
     */
    public function getLowStockFuels(float $threshold = 1000): \Illuminate\Database\Eloquent\Collection
    {
        return Fuel::where('stock_litres', '<', $threshold)->get();
    }
}
