<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create user
        $user = User::firstOrCreate(
            ['email' => 'janonemersion@hotmail.com'],
            [
                'name' => 'Janon Emersion T',
                'password' => Hash::make('Jj112112@!@'),
                'email_verified_at' => now()
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'pumpcontroller@hostmysite.com'],
            [
                'name' => 'Pump Control Admin',
                'password' => Hash::make('Pump@Controller@1234'),
                'email_verified_at' => now()
            ]
        );

        // Sample permissions
        $permissions = [
            'dashboard',

            'manage users',
            'create users',
            'read users',
            'update users',
            'delete users',

            'manage accounts',
            'create accounts',
            'read accounts',
            'update accounts',
            'delete accounts',

            'manage incomes',
            'create incomes',
            'read incomes',
            'update incomes',
            'delete incomes',

            'manage expenses',
            'create expenses',
            'read expenses',
            'update expenses',
            'delete expenses',


            'manage transfers',
            'create transfers',
            'read transfers',
            'update transfers',
            'delete transfers',
            
            'manage fuel',
            'create fuel',
            'read fuel',
            'update fuel',
            'delete fuel',

            'manage pump',
            'create pump',
            'read pump',
            'update pump',
            'delete pump',

            'manage supplier',
            'create supplier',
            'read supplier',
            'update supplier',
            'delete supplier',

            'manage fuel purchase',
            'create fuel purchase',
            'read fuel purchase',
            'update fuel purchase',
            'delete fuel purchase',

            

            'manage users',
            'manage roles',
            'view sales',
            'create invoice',
            'access reports',
            'access settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $roles = [
            'God',
            'Superadmin',
            'Admin',
            'Cashier',
            'Customer',
            'Accountant',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Assign permissions to roles
        Role::findByName('God')->syncPermissions(Permission::all());

        Role::findByName('Superadmin')->syncPermissions(Permission::all());

        Role::findByName('Admin')->syncPermissions([
            'manage users',
            'access reports',
        ]);

        Role::findByName('Cashier')->syncPermissions([
            'view sales',
            'create invoice',
        ]);

        Role::findByName('Customer')->syncPermissions([
        ]);

        Role::findByName('Accountant')->syncPermissions([
            'access reports',
        ]);

        // Assign role to user
        $user->assignRole('God');
    }
}
