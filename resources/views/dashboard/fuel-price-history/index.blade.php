<x-layouts.app :title="__('Fuel Price History')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fuel Price History</h2>
            <a href="{{ route('fuel-price-history.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                + Change Fuel Price
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-800 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Fuel Type</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Price/Litre</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Effective Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Changed By</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Notes</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($priceHistory as $index => $history)
                        <tr class="{{ $history->is_active ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $priceHistory->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $history->fuel->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                Rs. {{ number_format($history->price_per_litre, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $history->effective_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $history->user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($history->is_active)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Historical
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $history->notes ? Str::limit($history->notes, 50) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('fuel-price-history.show', $history) }}"
                                   class="mr-2 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    View
                                </a>
                                @unless($history->is_active)
                                    <form action="{{ route('fuel-price-history.destroy', $history) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this price history record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No price history records found. Click "Change Fuel Price" to add the first price change.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $priceHistory->links() }}
        </div>
    </div>
</x-layouts.app>
