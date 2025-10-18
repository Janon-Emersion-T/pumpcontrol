<x-layouts.app :title="__('Price History Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Price History Details</h2>
            <a href="{{ route('fuel-price-history.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to Price History
            </a>
        </div>

        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $fuelPriceHistory->fuel->name }} Price Change
                    </h3>
                    @if($fuelPriceHistory->is_active)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-800 dark:text-green-100">
                            Current Active Price
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            Historical Record
                        </span>
                    @endif
                </div>
            </div>

            <div class="px-6 py-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fuel Type</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $fuelPriceHistory->fuel->name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Price per Litre</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            Rs. {{ number_format($fuelPriceHistory->price_per_litre, 2) }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Effective Date</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $fuelPriceHistory->effective_date->format('d F Y') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ({{ $fuelPriceHistory->effective_date->diffForHumans() }})
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Changed By</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $fuelPriceHistory->user->name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $fuelPriceHistory->user->email }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Record Created</label>
                        <p class="mt-1 text-base text-gray-900 dark:text-white">
                            {{ $fuelPriceHistory->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                        <p class="mt-1 text-base text-gray-900 dark:text-white">
                            {{ $fuelPriceHistory->updated_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                </div>

                @if($fuelPriceHistory->notes)
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes / Reason for Change</label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white whitespace-pre-wrap">{{ $fuelPriceHistory->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div>
                @unless($fuelPriceHistory->is_active)
                    <form action="{{ route('fuel-price-history.destroy', $fuelPriceHistory) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this price history record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                            Delete Record
                        </button>
                    </form>
                @endunless
            </div>

            <a href="{{ route('fuel.show', $fuelPriceHistory->fuel) }}"
               class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                View Fuel Details
            </a>
        </div>
    </div>
</x-layouts.app>
