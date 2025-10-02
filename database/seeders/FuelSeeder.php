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
            ['name' => 'Diesel', 'price_per_litre' => 132.75, 'stock_litres' => 0, 'description' => 'Diesel fuel'],
            ['name' => 'Kerosene', 'price_per_litre' => 110.25, 'stock_litres' => 0, 'description' => 'Kerosene'],
            ['name' => 'Super Diesel', 'price_per_litre' => 132.75, 'stock_litres' => 0, 'description' => 'Super Diesel'],
            ['name' => 'Super Petrol', 'price_per_litre' => 400, 'stock_litres' => 0, 'description' => 'Super Petrol'],
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
