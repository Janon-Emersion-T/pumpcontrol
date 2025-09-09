<x-layouts.app :title="__('Fuel Purchase Details')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Fuel Purchase Details</h1>
            <a href="{{ route('fuel-purchases.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        <dl class="space-y-4 text-sm text-gray-800 dark:text-gray-200">
            <div>
                <dt class="font-medium">Pump</dt>
                <dd>{{ $fuelPurchase->pump->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Fuel Type</dt>
                <dd>{{ $fuelPurchase->fuel->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Supplier</dt>
                <dd>{{ $fuelPurchase->supplier->name ?? '—' }}</dd>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="font-medium">Liters</dt>
                    <dd>{{ $fuelPurchase->liters }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Price Per Liter</dt>
                    <dd>{{ number_format($fuelPurchase->price_per_liter, 2) }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Total Cost</dt>
                    <dd>{{ number_format($fuelPurchase->total_cost, 2) }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Purchase Date</dt>
                    <dd>{{ $fuelPurchase->purchase_date->format('Y-m-d') }}</dd>
                </div>
            </div>
            <div>
                <dt class="font-medium">Notes</dt>
                <dd>{{ $fuelPurchase->notes ?? '—' }}</dd>
            </div>
        </dl>

        <div class="pt-6">
            <a href="{{ route('fuel-purchases.edit', $fuelPurchase) }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-md shadow-sm transition">
                Edit Purchase
            </a>
        </div>
    </div>
</x-layouts.app>
