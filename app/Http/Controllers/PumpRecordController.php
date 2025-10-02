<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FuelPurchase;
use App\Models\Income;
use App\Models\MeterReading;
use App\Models\Pump;
use App\Models\PumpRecord;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PumpRecordController extends Controller
{
    public function index()
    {
        $records = PumpRecord::with(['pump.fuel', 'staff'])
            ->orderByDesc('record_date')
            ->paginate(15);

        $recentMeterReadings = MeterReading::with(['pump', 'fuel', 'user'])
            ->latest('reading_date')
            ->limit(10)
            ->get();

        $todayMeterReadingsCount = MeterReading::whereDate('reading_date', today())->count();
        $unverifiedReadingsCount = MeterReading::unverified()->count();

        return view('dashboard.pump_records.index', compact('records', 'recentMeterReadings', 'todayMeterReadingsCount', 'unverifiedReadingsCount'));
    }

    public function create()
    {
        $pumps = Pump::with('fuel', 'currentMeterReading')->get();
        $staff = Staff::all();

        return view('dashboard.pump_records.create', compact('pumps', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pump_id' => ['required', Rule::exists('pumps', 'id')],
            'record_date' => [
                'required', 'date',
                Rule::unique('pump_records')->where(fn ($query) => $query->where('pump_id', $request->pump_id)
                ),
            ],
            'closing_meter' => 'required|numeric|min:0',
            'staff_id' => ['nullable', Rule::exists('staff', 'id')],
        ]);

        DB::transaction(function () use ($request) {
            $pump = Pump::with(['fuel', 'currentMeterReading'])->findOrFail($request->pump_id);
            $userId = auth()->id();

            $opening_meter = $pump->currentMeterReading->current_meter_reading ?? 0;
            $closing_meter = $request->closing_meter;
            $price_per_litre = $pump->fuel->price_per_litre ?? 0;

            $litresSold = $closing_meter - $opening_meter;

            if ($litresSold < 0) {
                throw new \Exception('Invalid meter readings: closing meter must be greater than opening meter.');
            }

            $totalSales = $litresSold * $price_per_litre;
            $account = Account::where('code', '3001')->firstOrFail();

            $record = PumpRecord::create([
                'pump_id' => $pump->id,
                'fuel_id' => $pump->fuel_id,
                'record_date' => $request->record_date,
                'opening_meter' => $opening_meter,
                'closing_meter' => $closing_meter,
                'litres_sold' => $litresSold,
                'price_per_litre' => $price_per_litre,
                'total_sales' => $totalSales,
                'staff_id' => $request->staff_id,
            ]);

            Income::create([
                'account_id' => $account->id,
                'user_id' => $userId,
                'amount' => $totalSales,
                'description' => "Fuel Sales - {$pump->name} ({$request->record_date})",
                'date' => $request->record_date,
                'reference' => 'pump_record:'.$record->id,
            ]);

            if ($account->type === 'Income') {
                $account->increment('current_balance', $totalSales);
            }

            // Update current meter reading
            if ($pump->currentMeterReading) {
                $pump->currentMeterReading->update([
                    'previous_meter_reading' => $opening_meter,
                    'current_meter_reading' => $closing_meter,
                ]);
            } else {
                $pump->currentMeterReading()->create([
                    'previous_meter_reading' => 0,
                    'current_meter_reading' => $closing_meter,
                ]);
            }
        });

        return redirect()->route('pump-records.index')->with('success', 'Pump record saved and meter reading updated.');
    }

    public function show(PumpRecord $pumpRecord)
    {
        $pumpRecord->load(['pump.fuel', 'staff']);

        $relatedMeterReadings = MeterReading::where('pump_id', $pumpRecord->pump_id)
            ->whereDate('reading_date', $pumpRecord->record_date)
            ->with(['user', 'verifiedBy'])
            ->get();

        return view('dashboard.pump_records.show', compact('pumpRecord', 'relatedMeterReadings'));
    }

    public function edit(PumpRecord $pumpRecord)
    {
        $staff = Staff::all();

        return view('dashboard.pump_records.edit', compact('pumpRecord', 'staff'));
    }

    public function update(Request $request, PumpRecord $pumpRecord)
    {
        $request->validate([
            'opening_meter' => 'required|numeric|min:0',
            'closing_meter' => 'required|numeric|min:0',
            'price_per_litre' => 'required|numeric|min:0',
            'staff_id' => ['nullable', Rule::exists('staff', 'id')],
        ]);

        DB::transaction(function () use ($request, $pumpRecord) {
            $oldAmount = $pumpRecord->total_sales;

            $fuelPurchased = FuelPurchase::where('pump_id', $pumpRecord->pump_id)
                ->whereDate('purchase_date', $pumpRecord->record_date)
                ->sum('liters');

            $litresSold = $fuelPurchased > 0
                ? ($request->opening_meter + $fuelPurchased) - $request->closing_meter
                : $request->opening_meter - $request->closing_meter;

            if ($litresSold < 0) {
                throw new \Exception('Invalid meter readings: sales calculated as negative.');
            }

            $newAmount = $litresSold * $request->price_per_litre;

            $account = Account::where('code', '3001')->firstOrFail();
            $income = Income::where('reference', 'pump_record:'.$pumpRecord->id)->first();

            if ($income) {
                $income->update([
                    'amount' => $newAmount,
                    'description' => "Fuel Sales - {$pumpRecord->pump->name} ({$pumpRecord->record_date})",
                ]);

                if ($account->type === 'Income') {
                    $account->decrement('current_balance', $oldAmount);
                    $account->increment('current_balance', $newAmount);
                }
            }

            $pumpRecord->update([
                'opening_meter' => $request->opening_meter,
                'closing_meter' => $request->closing_meter,
                'litres_sold' => $litresSold,
                'price_per_litre' => $request->price_per_litre,
                'total_sales' => $newAmount,
                'staff_id' => $request->staff_id,
            ]);

            // Update current meter reading
            $pump = $pumpRecord->pump;
            if ($pump->currentMeterReading) {
                $pump->currentMeterReading->update([
                    'previous_meter_reading' => $request->opening_meter,
                    'current_meter_reading' => $request->closing_meter,
                ]);
            }
        });

        return redirect()->route('pump-records.index')->with('success', 'Pump record updated successfully.');
    }

    public function destroy(PumpRecord $pumpRecord)
    {
        DB::transaction(function () use ($pumpRecord) {
            $account = Account::where('code', '3001')->firstOrFail();
            $income = Income::where('reference', 'pump_record:'.$pumpRecord->id)->first();

            if ($income) {
                if ($account->type === 'Income') {
                    $account->decrement('current_balance', $income->amount);
                }
                $income->delete();
            }

            // Reset meter reading to opening meter
            $pump = $pumpRecord->pump;
            if ($pump->currentMeterReading) {
                $pump->currentMeterReading->update([
                    'current_meter_reading' => $pumpRecord->opening_meter,
                    'previous_meter_reading' => null,
                ]);
            }

            $pumpRecord->delete();
        });

        return redirect()->route('pump-records.index')->with('success', 'Pump record and income deleted.');
    }
}
