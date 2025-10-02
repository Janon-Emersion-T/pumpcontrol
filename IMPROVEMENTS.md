# 🚀 PETROL STATION SYSTEM - IMPROVEMENTS & ENHANCEMENTS

**Date:** October 2, 2025
**Version:** 2.0 (Enhanced)

---

## 📋 Table of Contents

1. [Service Classes](#service-classes)
2. [Model Enhancements](#model-enhancements)
3. [Code Organization](#code-organization)
4. [Usage Examples](#usage-examples)
5. [Best Practices](#best-practices)

---

## 🔧 Service Classes

### Overview
Service classes have been created to handle complex business logic, making controllers thin and code reusable.

### 1. **FuelService** (`app/Services/FuelService.php`)

Handles all fuel-related operations including purchases and adjustments.

**Methods:**
```php
// Purchase Operations
processPurchase(array $data): FuelPurchase
updatePurchase(FuelPurchase $purchase, array $data): FuelPurchase
deletePurchase(FuelPurchase $purchase): bool

// Adjustment Operations
processAdjustment(array $data): FuelAdjustment
updateAdjustment(FuelAdjustment $adjustment, array $data): FuelAdjustment
deleteAdjustment(FuelAdjustment $adjustment): bool

// Stock Queries
getCurrentStock(int $fuelId): float
hasSufficientStock(int $fuelId, float $requiredLiters): bool
getLowStockFuels(float $threshold = 1000): Collection
```

**Usage Example:**
```php
use App\Services\FuelService;

$fuelService = new FuelService();

// Process a purchase
$purchase = $fuelService->processPurchase([
    'pump_id' => 1,
    'fuel_id' => 1,
    'supplier_id' => 1,
    'user_id' => auth()->id(),
    'liters' => 5000,
    'price_per_liter' => 150,
    'purchase_date' => today(),
    'notes' => 'Bulk purchase',
]);

// Check stock
if ($fuelService->hasSufficientStock(1, 500)) {
    // Proceed with sale
}

// Get low stock fuels
$lowStockFuels = $fuelService->getLowStockFuels(1000);
```

---

### 2. **MeterReadingService** (`app/Services/MeterReadingService.php`)

Handles meter reading operations and sales tracking.

**Methods:**
```php
// Reading Operations
processMeterReading(array $data): MeterReading
updateMeterReading(MeterReading $reading, array $data): MeterReading
deleteMeterReading(MeterReading $reading): bool
verifyMeterReading(MeterReading $reading, int $userId): MeterReading

// Query Methods
getLastClosingReading(int $pumpId): float
getTodaysSalesSummary(): array
```

**Usage Example:**
```php
use App\Services\MeterReadingService;

$service = new MeterReadingService();

// Record a sale
$reading = $service->processMeterReading([
    'pump_id' => 1,
    'fuel_id' => 1,
    'user_id' => auth()->id(),
    'opening_reading' => 1000,
    'closing_reading' => 1500,
    'price_per_liter' => 350,
    'reading_date' => today(),
    'reading_time' => '08:00:00',
    'shift' => 'morning',
]);

// Get today's summary
$summary = $service->getTodaysSalesSummary();
/*
Returns:
[
    'total_readings' => 5,
    'total_liters' => 2500.00,
    'total_amount' => 875000.00,
    'by_fuel' => [
        'Petrol' => ['liters' => 1500, 'amount' => 525000, 'readings' => 3],
        'Diesel' => ['liters' => 1000, 'amount' => 350000, 'readings' => 2],
    ]
]
*/

// Verify a reading
$service->verifyMeterReading($reading, auth()->id());
```

---

## 📦 Model Enhancements

### 1. **Fuel Model** (`app/Models/Fuel.php`)

#### Query Scopes
```php
// Get fuels with low stock
$lowStockFuels = Fuel::lowStock(1000)->get();

// Get out of stock fuels
$outOfStockFuels = Fuel::outOfStock()->get();

// Get in stock fuels
$availableFuels = Fuel::inStock()->get();
```

#### Helper Methods
```php
$fuel = Fuel::find(1);

// Stock checks
$fuel->isLowStock(1000);          // bool
$fuel->isOutOfStock();            // bool
$fuel->hasSufficientStock(500);   // bool

// Today's data
$fuel->getTotalPurchasesToday();  // float (liters)
$fuel->getTotalSalesToday();      // float (liters)
$fuel->getRevenueToday();         // float (amount)

// Stock information
$fuel->getStockValue();           // float (liters * price)
$fuel->getFormattedStock();       // "4,500.00L"
$fuel->getFormattedPrice();       // "Rs.350.00"

// Attributes
$fuel->stock_status;              // 'in_stock', 'low_stock', 'out_of_stock'
$fuel->stock_status_color;        // 'green', 'yellow', 'red'
```

**Usage in Blade:**
```blade
@foreach($fuels as $fuel)
    <div class="fuel-card bg-{{ $fuel->stock_status_color }}-100">
        <h3>{{ $fuel->name }}</h3>
        <p>Stock: {{ $fuel->getFormattedStock() }}</p>
        <p>Price: {{ $fuel->getFormattedPrice() }}</p>
        <p>Value: Rs.{{ number_format($fuel->getStockValue(), 2) }}</p>

        @if($fuel->isLowStock())
            <span class="badge badge-warning">Low Stock</span>
        @endif

        @if($fuel->isOutOfStock())
            <span class="badge badge-danger">Out of Stock</span>
        @endif
    </div>
@endforeach
```

---

### 2. **Pump Model** (`app/Models/Pump.php`)

#### Query Scopes
```php
// Get active pumps only
$activePumps = Pump::active()->get();

// Get inactive pumps
$inactivePumps = Pump::inactive()->get();

// Get pumps for specific fuel
$petrolPumps = Pump::byFuel(1)->get();
```

#### Helper Methods
```php
$pump = Pump::find(1);

// Status management
$pump->activate();                      // Set pump as active
$pump->deactivate();                    // Set pump as inactive

// Readings
$pump->getLastClosingReading();         // float
$pump->hasReadingsToday();              // bool

// Sales data
$pump->getTodaysSales();                // float (liters)
$pump->getTodaysRevenue();              // float (amount)
$pump->getTotalSalesThisMonth();        // float (liters)
$pump->getRevenueThisMonth();           // float (amount)

// Display helpers
$pump->getStatusBadgeColor();           // 'green' or 'red'
$pump->getStatusText();                 // 'Active' or 'Inactive'
```

**Usage in Blade:**
```blade
@foreach($pumps as $pump)
    <div class="pump-card">
        <h4>{{ $pump->name }}</h4>
        <span class="badge badge-{{ $pump->getStatusBadgeColor() }}">
            {{ $pump->getStatusText() }}
        </span>

        <p>Today's Sales: {{ number_format($pump->getTodaysSales(), 2) }}L</p>
        <p>Today's Revenue: Rs.{{ number_format($pump->getTodaysRevenue(), 2) }}</p>

        @if($pump->hasReadingsToday())
            <span class="text-success">✓ Readings recorded</span>
        @else
            <span class="text-warning">⚠ No readings today</span>
        @endif
    </div>
@endforeach
```

---

### 3. **Account Model** (`app/Models/Account.php`)

#### Query Scopes
```php
// Get accounts by type
$assets = Account::assets()->get();
$liabilities = Account::liabilities()->get();
$incomeAccounts = Account::incomeAccounts()->get();
$expenseAccounts = Account::expenseAccounts()->get();

// Get parent accounts only
$parents = Account::parents()->get();

// Get specific type
$cashAccounts = Account::byType('Asset')->get();
```

#### Helper Methods
```php
$account = Account::find(1);

// Status checks
$account->isActive();                   // bool
$account->isParent();                   // bool
$account->hasChildren();                // bool

// Today's transactions
$account->getTotalIncomesToday();       // float
$account->getTotalExpensesToday();      // float

// This month's transactions
$account->getTotalIncomesThisMonth();   // float
$account->getTotalExpensesThisMonth();  // float

// Display helpers
$account->getFormattedBalance();        // "Rs.1,500,000.00"
$account->getBalanceColor();            // 'green', 'red', 'gray'
$account->getFullName();                // "Parent > Child"
```

**Usage in Blade:**
```blade
@foreach($accounts as $account)
    <tr>
        <td>{{ $account->code }}</td>
        <td>{{ $account->getFullName() }}</td>
        <td class="text-{{ $account->getBalanceColor() }}">
            {{ $account->getFormattedBalance() }}
        </td>
        <td>
            @if($account->isActive())
                <span class="badge badge-success">Active</span>
            @else
                <span class="badge badge-secondary">Inactive</span>
            @endif
        </td>
    </tr>
@endforeach
```

---

## 📚 Code Organization

### Before (Controller Logic)
```php
// FuelPurchaseController (BEFORE)
public function store(Request $request)
{
    $request->validate([...]);

    DB::transaction(function () use ($request) {
        $totalCost = $request->liters * $request->price_per_liter;
        $account = Account::where('code', '2001')->firstOrFail();

        $purchase = FuelPurchase::create([...]);
        Expense::create([...]);

        if ($account->type === 'Expense') {
            $account->decrement('current_balance', $totalCost);
        }

        $fuel = Fuel::findOrFail($request->fuel_id);
        $fuel->increment('stock_litres', $request->liters);
    });

    return redirect()->route('fuel-purchases.index');
}
```

### After (Using Service)
```php
// FuelPurchaseController (AFTER)
public function __construct(private FuelService $fuelService) {}

public function store(StoreFuelPurchaseRequest $request)
{
    try {
        $purchase = $this->fuelService->processPurchase([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('fuel-purchases.index')
            ->with('success', 'Fuel purchase recorded successfully.');

    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => $e->getMessage()])
            ->withInput();
    }
}
```

---

## 💡 Usage Examples

### Example 1: Processing Daily Sales

```php
use App\Services\MeterReadingService;

class DailySalesReportController extends Controller
{
    public function __construct(private MeterReadingService $service) {}

    public function index()
    {
        $summary = $this->service->getTodaysSalesSummary();
        $fuels = Fuel::with('pumps')->get();

        $fuelStats = $fuels->map(function ($fuel) {
            return [
                'name' => $fuel->name,
                'stock' => $fuel->getFormattedStock(),
                'sales_today' => $fuel->getTotalSalesToday(),
                'revenue_today' => $fuel->getRevenueToday(),
                'status' => $fuel->stock_status,
            ];
        });

        return view('reports.daily-sales', compact('summary', 'fuelStats'));
    }
}
```

### Example 2: Stock Management Dashboard

```php
use App\Services\FuelService;

class StockDashboardController extends Controller
{
    public function __construct(private FuelService $fuelService) {}

    public function index()
    {
        // Get all stock information
        $lowStockFuels = $this->fuelService->getLowStockFuels(1000);
        $allFuels = Fuel::withCount('pumps')->get();

        // Calculate totals
        $totalStockValue = $allFuels->sum(function ($fuel) {
            return $fuel->getStockValue();
        });

        $stockAlerts = $allFuels->filter(function ($fuel) {
            return $fuel->isLowStock() || $fuel->isOutOfStock();
        });

        return view('dashboard.stock', compact(
            'lowStockFuels',
            'allFuels',
            'totalStockValue',
            'stockAlerts'
        ));
    }
}
```

### Example 3: Monthly Financial Report

```php
class FinancialReportController extends Controller
{
    public function monthly()
    {
        $accounts = Account::with(['incomes', 'expenses'])->get();

        $report = $accounts->map(function ($account) {
            return [
                'account' => $account->getFullName(),
                'type' => $account->type,
                'balance' => $account->getFormattedBalance(),
                'balance_color' => $account->getBalanceColor(),
                'income_this_month' => $account->getTotalIncomesThisMonth(),
                'expenses_this_month' => $account->getTotalExpensesThisMonth(),
            ];
        });

        return view('reports.monthly-financial', compact('report'));
    }
}
```

---

## ✅ Best Practices

### 1. **Always Use Services for Business Logic**

❌ **Bad:**
```php
public function store(Request $request)
{
    DB::transaction(function () use ($request) {
        $fuel = Fuel::find($request->fuel_id);
        $fuel->increment('stock_litres', $request->liters);
        // More business logic here...
    });
}
```

✅ **Good:**
```php
public function store(Request $request)
{
    $this->fuelService->processPurchase($request->validated());
}
```

### 2. **Use Model Scopes for Common Queries**

❌ **Bad:**
```php
$lowStockFuels = Fuel::where('stock_litres', '<', 1000)->get();
```

✅ **Good:**
```php
$lowStockFuels = Fuel::lowStock(1000)->get();
```

### 3. **Use Helper Methods in Views**

❌ **Bad:**
```blade
<td>Rs.{{ number_format($fuel->stock_litres * $fuel->price_per_litre, 2) }}</td>
```

✅ **Good:**
```blade
<td>Rs.{{ number_format($fuel->getStockValue(), 2) }}</td>
```

### 4. **Leverage Model Attributes**

❌ **Bad:**
```blade
@if($fuel->stock_litres < 1000)
    <span class="text-yellow-500">Low Stock</span>
@elseif($fuel->stock_litres <= 0)
    <span class="text-red-500">Out of Stock</span>
@else
    <span class="text-green-500">In Stock</span>
@endif
```

✅ **Good:**
```blade
<span class="text-{{ $fuel->stock_status_color }}-500">
    {{ ucfirst(str_replace('_', ' ', $fuel->stock_status)) }}
</span>
```

---

## 🎯 Benefits of These Improvements

1. **✅ Cleaner Controllers** - Business logic moved to services
2. **✅ Reusable Code** - Services can be used anywhere
3. **✅ Easier Testing** - Services are easier to unit test
4. **✅ Better Organization** - Clear separation of concerns
5. **✅ Improved Maintainability** - Changes in one place
6. **✅ Enhanced Readability** - Descriptive method names
7. **✅ Type Safety** - Proper return types defined
8. **✅ Better Performance** - Optimized queries with scopes

---

## 📝 Migration Guide

### To use services in existing controllers:

1. **Inject the service:**
```php
public function __construct(private FuelService $fuelService) {}
```

2. **Replace transaction logic:**
```php
// Old
DB::transaction(function () { ... });

// New
$this->fuelService->processPurchase($data);
```

3. **Handle exceptions:**
```php
try {
    $result = $this->service->process($data);
    return redirect()->back()->with('success', 'Done!');
} catch (\Exception $e) {
    return back()->withErrors(['error' => $e->getMessage()]);
}
```

---

**Your application now follows industry best practices and is highly maintainable!** 🎉
