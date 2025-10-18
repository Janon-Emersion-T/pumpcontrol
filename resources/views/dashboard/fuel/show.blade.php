<x-layouts.app :title="__('Fuel Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fuel Details</h2>
            <a href="{{ route('fuel.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <span class="font-semibold">Name:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $fuel->name }}</div>
            </div>

            <div>
                <span class="font-semibold">Price per Litre:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ number_format($fuel->price_per_litre, 2) }}</div>
            </div>

            <div>
                <span class="font-semibold">Stock (Litres):</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ number_format($fuel->stock_litres, 2) }}</div>
            </div>

            <div class="md:col-span-2">
                <span class="font-semibold">Description:</span>
                <div class="mt-1 text-gray-900 dark:text-white">
                    {{ $fuel->description ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Price History Section -->
        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Price History</h3>
                <a href="{{ route('fuel-price-history.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
                    Change Price
                </a>
            </div>

            @if($priceHistory->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Price/Litre</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Effective Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Changed By</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($priceHistory as $history)
                                <tr class="{{ $history->is_active ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        Rs. {{ number_format($history->price_per_litre, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $history->effective_date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $history->user->name }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($history->is_active)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                Historical
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Showing latest 10 price changes. <a href="{{ route('fuel-price-history.index') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">View all price history</a>
                </p>
            @else
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No price history available</p>
                </div>
            @endif
        </div>

        <div class="pt-6 flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 mt-6">
            <a href="{{ route('fuel.edit', $fuel) }}"
               class="inline-flex items-center rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                Edit
            </a>
            <form action="{{ route('fuel.destroy', $fuel) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this fuel entry?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
