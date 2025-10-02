<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        /* PaymentMethod::insert([
            [
                'name' => 'Cash',
                'code' => 'cash',
                'account_code' => '4001', // Sales Income
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Card',
                'code' => 'card',
                'account_code' => '4004', // Sales Card Income
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]); */
    }
}
