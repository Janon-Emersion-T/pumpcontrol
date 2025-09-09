<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AccountController,
    AccountTransferController,
    DashboardController,
    FuelController,
    FuelPurchaseController,
    FuelAdjustmentController,
    IncomeController,
    ExpenseController,
    PumpController,
    PumpRecordController,
    SupplierController,
    UserController,
    RoleController,
    StaffController
};
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use App\Models\Pump;

/*
|--------------------------------------------------------------------------
| AJAX: Get Fuel by Pump ID
|--------------------------------------------------------------------------
*/
Route::get('/pumps/{pump}/fuel', function (Pump $pump) {
    $fuel = $pump->fuel;
    return $fuel
        ? response()->json(['id' => $fuel->id, 'name' => $fuel->name])
        : response()->json(['id' => null, 'name' => 'N/A']);
})->name('pump.fuel');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Settings
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/settings', '/settings/profile');
    Route::get('/settings/profile', Profile::class)->name('settings.profile');
    Route::get('/settings/password', Password::class)->name('settings.password');
    Route::get('/settings/appearance', Appearance::class)->name('settings.appearance');

    /*
    |--------------------------------------------------------------------------
    | User & Role Management
    |--------------------------------------------------------------------------
    */
    Route::resource('dashboard/users', UserController::class)->names('users');
    Route::resource('dashboard/roles', RoleController::class)->names('roles');
    Route::resource('staff', StaffController::class)->names('staff');

    /*
    |--------------------------------------------------------------------------
    | Accounting
    |--------------------------------------------------------------------------
    | Keep existing name patterns (accounts.*, incomes.*, expenses.*, account-transfers.*)
    */
    Route::resource('dashboard/accounts', AccountController::class)->names('accounts');
    Route::resource('dashboard/incomes', IncomeController::class)->names('incomes');
    Route::resource('dashboard/expenses', ExpenseController::class)->names('expenses');
    Route::resource('dashboard/account-transfers', AccountTransferController::class)->names('account-transfers');

    /*
    |--------------------------------------------------------------------------
    | Fuel System
    |--------------------------------------------------------------------------
    | Provide BOTH name sets for Fuel:
    |   - dashboard.fuel.*   → used by your controller redirects
    |   - fuel.*             → legacy references in views/links (kept for compatibility)
    */

    // URLs under /dashboard/fuel with names dashboard.fuel.*
    Route::resource('dashboard/fuel', FuelController::class)->names('dashboard.fuel');

    // Parallel URLs under /fuel with names fuel.* (auth-protected)
    Route::resource('fuel', FuelController::class)->names('fuel');

    // Other fuel-related resources keep their existing (unprefixed) names
    Route::resource('dashboard/pump', PumpController::class)->names('pump');
    Route::resource('dashboard/pump-records', PumpRecordController::class)->names('pump-records');
    Route::resource('dashboard/fuel-purchases', FuelPurchaseController::class)->names('fuel-purchases');
    Route::resource('dashboard/fuel-adjustments', FuelAdjustmentController::class)->names('fuel-adjustments');

    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    */
    Route::resource('dashboard/supplier', SupplierController::class)->names('supplier');
});

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

require __DIR__ . '/auth.php';
