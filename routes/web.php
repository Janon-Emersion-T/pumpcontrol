<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FuelAdjustmentController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\FuelPriceHistoryController;
use App\Http\Controllers\FuelPurchaseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MeterReadingController;
use App\Http\Controllers\PumpController;
use App\Http\Controllers\PumpRecordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Pump;
use Illuminate\Support\Facades\Route;

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
    | Fuel Management System
    |--------------------------------------------------------------------------
    | Organized fuel management routes with consistent naming and structure
    */

    // Meter Readings (must come BEFORE the fuel resource routes to avoid conflicts)
    Route::resource('dashboard/fuel/meter-readings', MeterReadingController::class)->names('fuel.meter-readings');
    Route::patch('dashboard/fuel/meter-readings/{meterReading}/verify', [MeterReadingController::class, 'verify'])->name('fuel.meter-readings.verify');

    // Fuel Types Management (provide both naming conventions)
    Route::resource('dashboard/fuel', FuelController::class)->names('dashboard.fuel');
    Route::resource('fuel', FuelController::class)->names('fuel'); // Legacy compatibility

    // Pump Management
    Route::resource('dashboard/pump', PumpController::class)->names('pump');

    // Pump Operations
    Route::resource('dashboard/pump-records', PumpRecordController::class)->names('pump-records');

    // Fuel Operations
    Route::resource('dashboard/fuel-purchases', FuelPurchaseController::class)->names('fuel-purchases');
    Route::resource('dashboard/fuel-adjustments', FuelAdjustmentController::class)->names('fuel-adjustments');
    Route::resource('dashboard/fuel-price-history', FuelPriceHistoryController::class)->names('fuel-price-history');

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

require __DIR__.'/auth.php';
