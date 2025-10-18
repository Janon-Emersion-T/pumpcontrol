<?php

namespace Database\Seeders;

use App\Models\Fuel;
use App\Models\FuelPriceHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class FuelPriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates initial price history records for all existing fuels
     * based on their current prices.
     */
    public function run(): void
    {
        // Get the first admin user or create a system user
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found in database. Please run UserSeeder first.');
            return;
        }

        // Get all fuels
        $fuels = Fuel::all();

        if ($fuels->isEmpty()) {
            $this->command->warn('No fuels found in database. Please run FuelSeeder first.');
            return;
        }

        $this->command->info('Creating price history for existing fuels...');

        foreach ($fuels as $fuel) {
            // Check if this fuel already has a price history
            $existingHistory = FuelPriceHistory::where('fuel_id', $fuel->id)->first();

            if ($existingHistory) {
                $this->command->info("Fuel '{$fuel->name}' already has price history. Skipping...");
                continue;
            }

            // Create initial price history record
            FuelPriceHistory::create([
                'fuel_id' => $fuel->id,
                'price_per_litre' => $fuel->price_per_litre,
                'effective_date' => now()->subDays(30), // Set initial price as 30 days ago
                'user_id' => $user->id,
                'notes' => 'Initial price record - migrated from existing fuel data',
                'is_active' => true,
            ]);

            $this->command->info("Created price history for '{$fuel->name}' at Rs. {$fuel->price_per_litre}");
        }

        $this->command->info('Fuel price history seeding completed!');
    }
}
