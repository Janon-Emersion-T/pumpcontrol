<?php

namespace Database\Seeders;

use App\Models\MeterReading;
use App\Models\Pump;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeterReadingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $pumps = Pump::with('fuel')->get();

        if (! $user) {
            $this->command->warn('No users found. Please create a user first.');

            return;
        }

        foreach ($pumps as $pump) {
            // Create initial meter readings for each pump
            $baseReading = rand(500, 2000); // Random starting point
            $dispensed = rand(50, 200); // Random amount dispensed

            MeterReading::create([
                'pump_id' => $pump->id,
                'fuel_id' => $pump->fuel_id,
                'user_id' => $user->id,
                'opening_reading' => $baseReading,
                'closing_reading' => $baseReading + $dispensed,
                'total_dispensed' => $dispensed,
                'price_per_liter' => $pump->fuel->price_per_litre,
                'total_amount' => $dispensed * $pump->fuel->price_per_litre,
                'reading_date' => now()->format('Y-m-d'),
                'reading_time' => '08:00:00',
                'shift' => 'morning',
                'notes' => 'Initial seeded reading for '.$pump->name,
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $user->id,
            ]);
        }

        $this->command->info('Created initial meter readings for all pumps.');
    }
}
