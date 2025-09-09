<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accountData = [
            // Expense Accounts
            ['code' => '2000', 'name' => 'Expenses', 'type' => 'Expense', 'description' => 'All expense categories'],
            ['code' => '2001', 'name' => 'Fuel Expense', 'type' => 'Expense', 'description' => 'Expenses on fuel', 'parent_code' => '2000'],

            // Income Accounts
            ['code' => '3000', 'name' => 'Income', 'type' => 'Income', 'description' => 'All income sources'],
            ['code' => '3001', 'name' => 'Fuel Sales Income', 'type' => 'Income', 'description' => 'Income from fuel sales', 'parent_code' => '3000'],
        ];

        $accountIdMap = [];

        foreach ($accountData as $data) {
            $parentId = null;
            if (isset($data['parent_code'])) {
                $parentId = $accountIdMap[$data['parent_code']] ?? null;
            }

            $account = Account::firstOrCreate(['code' => $data['code']], [
                'name' => $data['name'],
                'type' => $data['type'],
                'parent_id' => $parentId,
                'description' => $data['description'],
                'current_balance' => 0,
                'is_active' => true,
            ]);

            $accountIdMap[$data['code']] = $account->id;
        }
    }
}
