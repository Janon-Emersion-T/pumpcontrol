<x-layouts.app :title="__('Edit Fuel Purchase')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Fuel Purchase</h1>
            <a href="{{ route('fuel-purchases.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fuel-purchases.update', $fuelPurchase) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Liters & Price -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Liters</label>
                    <input type="number" step="0.01" name="liters" value="{{ old('liters', $fuelPurchase->liters) }}" required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Price Per Liter</label>
                    <input type="number" step="0.01" name="price_per_liter" value="{{ old('price_per_liter', $fuelPurchase->price_per_liter) }}" required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', $fuelPurchase->purchase_date->format('Y-m-d')) }}" required
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('notes', $fuelPurchase->notes) }}</textarea>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-md shadow-sm transition">
                    Update Purchase
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
