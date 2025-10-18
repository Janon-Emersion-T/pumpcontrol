<x-layouts.app :title="__('Edit Fuel')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Fuel</h2>
            <a href="{{ route('fuel.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to List
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

        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Price Change Warning</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p><strong>Important:</strong> Changing the price here will NOT track price history or apply it correctly to transactions.</p>
                        <p class="mt-2">To change fuel prices properly with full tracking and historical records, please use:</p>
                        <a href="{{ route('fuel-price-history.create') }}"
                           class="mt-2 inline-flex items-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500">
                            Price History Management →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('fuel.update', $fuel) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fuel Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $fuel->name) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="price_per_litre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Litre</label>
                <input type="number" name="price_per_litre" id="price_per_litre" step="0.01"
                       value="{{ old('price_per_litre', $fuel->price_per_litre) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="stock_litres" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock (Litres)</label>
                <input type="number" name="stock_litres" id="stock_litres" step="0.01"
                       value="{{ old('stock_litres', $fuel->stock_litres) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $fuel->description) }}</textarea>
            </div>

            <div>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    Update Fuel
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
