<x-layouts.app :title="__('Change Fuel Price')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Change Fuel Price</h2>
            <a href="{{ route('fuel-price-history.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to Price History
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-800 dark:text-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Important Information</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>When you change a fuel price:</p>
                        <ul class="list-disc pl-5 mt-1 space-y-1">
                            <li>The new price will take effect from the date you specify</li>
                            <li>All transactions <strong>before</strong> the effective date will use the <strong>old price</strong></li>
                            <li>All transactions <strong>on or after</strong> the effective date will use the <strong>new price</strong></li>
                            <li>Historical price data is preserved and cannot be changed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('fuel-price-history.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="fuel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Fuel Type</label>
                    <select name="fuel_id" id="fuel_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- Select Fuel --</option>
                        @foreach($fuels as $fuel)
                            <option value="{{ $fuel->id }}" {{ old('fuel_id') == $fuel->id ? 'selected' : '' }}>
                                {{ $fuel->name }} (Current: Rs. {{ number_format($fuel->price_per_litre, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('fuel_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_litre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Price per Litre (Rs.)</label>
                    <input type="number" name="price_per_litre" id="price_per_litre" step="0.01" min="0"
                           value="{{ old('price_per_litre') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="e.g., 350.00">
                    @error('price_per_litre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="effective_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Effective Date</label>
                <input type="date" name="effective_date" id="effective_date"
                       value="{{ old('effective_date', date('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    The date from which this price will be applied to new transactions
                </p>
                @error('effective_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes / Reason for Change</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="e.g., Market price increase, supplier cost adjustment, etc.">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Optional: Document why this price change was made
                </p>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('fuel-price-history.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:ring-offset-gray-900">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    Update Fuel Price
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
