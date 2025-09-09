<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fuel;

class FuelSeeder extends Seeder
{
    public function run(): void
    {
        $fuels = [
            ['name' => 'Petrol', 'price_per_litre' => 350, 'stock_litres' => 0, 'description' => 'Regular unleaded petrol'],
            ['name' => 'Super Petrol', 'price_per_litre' => 400, 'stock_litres' => 0, 'description' => 'Super Petrol'],
            ['name' => 'Diesel', 'price_per_litre' => 132.75, 'stock_litres' => 0, 'description' => 'Diesel fuel'],
            ['name' => 'Super Diesel', 'price_per_litre' => 132.75, 'stock_litres' => 0, 'description' => 'Super Diesel'],
            ['name' => 'Kerosene', 'price_per_litre' => 110.25, 'stock_litres' => 0, 'description' => 'Kerosene'],
            ['name' => 'Oil-40', 'price_per_litre' => 100, 'stock_litres' => 0, 'description' => 'Oil Grade 40'],
            ['name' => 'Oil-50', 'price_per_litre' => 110.25, 'stock_litres' => 0, 'description' => 'Oil Grade 50'],
            ['name' => 'Oil-60', 'price_per_litre' => 120.50, 'stock_litres' => 0, 'description' => 'Oil Grade 60'],
            ['name' => 'Oil-70', 'price_per_litre' => 130.75, 'stock_litres' => 0, 'description' => 'Oil Grade 70'],
            ['name' => 'Oil-80', 'price_per_litre' => 140.00, 'stock_litres' => 0, 'description' => 'Oil Grade 80'],
        ];

        foreach ($fuels as $data) {
            Fuel::firstOrCreate(
                ['name' => $data['name']],
                [
                    'price_per_litre' => $data['price_per_litre'],
                    'stock_litres' => $data['stock_litres'],
                    'description' => $data['description'],
                ]
            );
        }
    }
}
