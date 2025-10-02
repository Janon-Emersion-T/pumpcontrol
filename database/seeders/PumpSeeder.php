<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pump;
use App\Models\Fuel;

class PumpSeeder extends Seeder
{
    public function run(): void
    {
        // Define number of pumps for each fuel type
        $pumpConfig = [
            'Petrol' => 3,
            'Diesel' => 3,
            'Kerosene' => 2,
            'Super Diesel' => 1,
            'Super Petrol' => 1,
        ];

        foreach ($pumpConfig as $fuelName => $pumpCount) {
            $fuel = Fuel::where('name', $fuelName)->first();

            if (!$fuel) {
                $this->command->warn("⚠️ Fuel '{$fuelName}' not found. Skipping...");
                continue;
            }

            // Create the specified number of pumps for this fuel type
            for ($i = 1; $i <= $pumpCount; $i++) {
                Pump::firstOrCreate(
                    [
                        'name' => "{$fuel->name} Pump {$i}",
                        'fuel_id' => $fuel->id,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ Pumps seeded successfully.');
    }
}
