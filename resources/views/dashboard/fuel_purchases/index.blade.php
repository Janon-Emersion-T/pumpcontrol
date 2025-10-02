<x-layouts.app :title="__('Fuel Purchases')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Fuel Purchases</h2>
            <a href="{{ route('fuel-purchases.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition text-sm font-medium">
                + Add Purchase
            </a>
        </div>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-3 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Pump</th>
                        <th class="px-4 py-2">Fuel</th>
                        <th class="px-4 py-2">Supplier</th>
                        <th class="px-4 py-2 text-right">Liters</th>
                        <th class="px-4 py-2 text-right">Price/L</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-4 py-2">{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $purchase->pump->name }}</td>
                            <td class="px-4 py-2">{{ $purchase->fuel->name }}</td>
                            <td class="px-4 py-2">{{ $purchase->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($purchase->liters, 2) }}</td>
                            <td class="px-4 py-2 text-right">Rs {{ number_format($purchase->price_per_liter, 2) }}</td>
                            <td class="px-4 py-2 text-right font-semibold">Rs {{ number_format($purchase->total_cost, 2) }}</td>
                            <td class="px-4 py-2 text-right space-x-1">
                                <a href="{{ route('fuel-purchases.show', $purchase) }}"
                                   class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('fuel-purchases.edit', $purchase) }}"
                                   class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('fuel-purchases.destroy', $purchase) }}" method="POST"
                                      class="inline" onsubmit="return confirm('Delete this purchase?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                No fuel purchases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $purchases->links() }}
        </div>
    </div>
</x-layouts.app>
