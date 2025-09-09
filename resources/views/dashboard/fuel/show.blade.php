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

        <div class="pt-6 flex justify-end gap-3">
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
