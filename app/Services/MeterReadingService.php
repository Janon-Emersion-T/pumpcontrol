<?php

namespace App\Services;

use App\Models\Fuel;
use App\Models\MeterReading;
use App\Models\Account;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class MeterReadingService
{
    /**
     * Process a meter reading (fuel sale)
     */
    public function processMeterReading(array $data): MeterReading
    {
        return DB::transaction(function () use ($data) {
            $totalDispensed = $data['closing_reading'] - $data['opening_reading'];
            $totalAmount = $totalDispensed * $data['price_per_liter'];

            // Check stock availability
            $fuel = Fuel::findOrFail($data['fuel_id']);
            if ($fuel->stock_litres < $totalDispensed) {
                throw new \Exception("Insufficient fuel stock. Available: {$fuel->stock_litres}L, Required: {$totalDispensed}L");
            }

            // Create meter reading
            $meterReading = MeterReading::create([
                'pump_id' => $data['pump_id'],
                'fuel_id' => $data['fuel_id'],
                'user_id' => $data['user_id'],
                'opening_reading' => $data['opening_reading'],
                'closing_reading' => $data['closing_reading'],
                'price_per_liter' => $data['price_per_liter'],
                'reading_date' => $data['reading_date'],
                'reading_time' => $data['reading_time'],
                'shift' => $data['shift'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Deduct from stock
            $fuel->decrement('stock_litres', $totalDispensed);

            // Create income record
            $incomeAccount = Account::where('code', '3001')->first();
            if ($incomeAccount) {
                Income::create([
                    'account_id' => $incomeAccount->id,
                    'user_id' => $data['user_id'],
                    'amount' => $totalAmount,
                    'description' => "Fuel Sales - {$fuel->name} - Pump #{$data['pump_id']} - {$data['shift']} shift",
                    'date' => $data['reading_date'],
                    'reference' => 'meter_reading:' . $meterReading->id,
                ]);

                $incomeAccount->increment('current_balance', $totalAmount);
            }

            return $meterReading->fresh(['pump', 'fuel', 'user']);
        });
    }

    /**
     * Update a meter reading
     */
    public function updateMeterReading(MeterReading $meterReading, array $data): MeterReading
    {
        return DB::transaction(function () use ($meterReading, $data) {
            $fuel = Fuel::findOrFail($data['fuel_id']);

            // Calculate old and new amounts
            $oldTotalDispensed = $meterReading->total_dispensed;
            $oldTotalAmount = $meterReading->total_amount;
            $newTotalDispensed = $data['closing_reading'] - $data['opening_reading'];
            $newTotalAmount = $newTotalDispensed * $data['price_per_liter'];

            // Revert old stock deduction
            $fuel->increment('stock_litres', $oldTotalDispensed);

            // Check new stock availability
            if ($fuel->stock_litres < $newTotalDispensed) {
                throw new \Exception("Insufficient fuel stock. Available: {$fuel->stock_litres}L, Required: {$newTotalDispensed}L");
            }

            // Apply new stock deduction
            $fuel->decrement('stock_litres', $newTotalDispensed);

            // Update income record
            $reference = 'meter_reading:' . $meterReading->id;
            $income = Income::where('reference', $reference)->first();
            if ($income) {
                $account = $income->account;

                // Revert old income
                $account->decrement('current_balance', $oldTotalAmount);

                // Update income
                $income->update([
                    'amount' => $newTotalAmount,
                    'description' => "Fuel Sales - {$fuel->name} - Pump #{$data['pump_id']} - {$data['shift']} shift",
                    'date' => $data['reading_date'],
                ]);

                // Apply new income
                $account->increment('current_balance', $newTotalAmount);
            }

            // Update meter reading
            $meterReading->update([
                'pump_id' => $data['pump_id'],
                'fuel_id' => $data['fuel_id'],
                'opening_reading' => $data['opening_reading'],
                'closing_reading' => $data['closing_reading'],
                'price_per_liter' => $data['price_per_liter'],
                'reading_date' => $data['reading_date'],
                'reading_time' => $data['reading_time'],
                'shift' => $data['shift'],
                'notes' => $data['notes'] ?? null,
            ]);

            return $meterReading->fresh(['pump', 'fuel', 'user']);
        });
    }

    /**
     * Delete a meter reading
     */
    public function deleteMeterReading(MeterReading $meterReading): bool
    {
        return DB::transaction(function () use ($meterReading) {
            // Revert fuel stock
            $fuel = Fuel::findOrFail($meterReading->fuel_id);
            $fuel->increment('stock_litres', $meterReading->total_dispensed);

            // Delete income record
            $reference = 'meter_reading:' . $meterReading->id;
            $income = Income::where('reference', $reference)->first();
            if ($income) {
                $account = $income->account;
                $account->decrement('current_balance', $income->amount);
                $income->delete();
            }

            return $meterReading->delete();
        });
    }

    /**
     * Verify a meter reading
     */
    public function verifyMeterReading(MeterReading $meterReading, int $userId): MeterReading
    {
        $meterReading->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $userId,
        ]);

        return $meterReading->fresh(['verifiedBy']);
    }

    /**
     * Get last closing reading for a pump
     */
    public function getLastClosingReading(int $pumpId): float
    {
        return MeterReading::lastClosingReading($pumpId);
    }

    /**
     * Get today's sales summary
     */
    public function getTodaysSalesSummary(): array
    {
        $readings = MeterReading::with(['fuel'])
            ->whereDate('reading_date', today())
            ->get();

        return [
            'total_readings' => $readings->count(),
            'total_liters' => $readings->sum('total_dispensed'),
            'total_amount' => $readings->sum('total_amount'),
            'by_fuel' => $readings->groupBy('fuel.name')->map(function ($group) {
                return [
                    'liters' => $group->sum('total_dispensed'),
                    'amount' => $group->sum('total_amount'),
                    'readings' => $group->count(),
                ];
            }),
        ];
    }
}
