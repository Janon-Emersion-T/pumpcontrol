<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pump;
use App\Models\Fuel;

class PumpSeeder extends Seeder
{
    public function run(): void
    {
        $fuels = Fuel::all();

        if ($fuels->isEmpty()) {
            $this->command->warn('⚠️ No fuels found. Please seed the fuels table first.');
            return;
        }

        foreach ($fuels as $fuel) {
            // Create two pumps per fuel type
            for ($i = 1; $i <= 2; $i++) {
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
