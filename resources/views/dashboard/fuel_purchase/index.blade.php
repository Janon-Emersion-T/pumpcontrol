<x-layouts.app :title="__('Fuel Purchases')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fuel Purchase Records</h2>
            <a href="{{ route('fuel_purchase.create') }}" class="btn-primary">+ Add Purchase</a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Fuel</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Qty (L)</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Unit Price</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Total</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($purchases as $index => $purchase)
                        <tr>
                            <td class="px-4 py-3">{{ $purchases->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $purchase->fuel->name }}</td>
                            <td class="px-4 py-3">{{ number_format($purchase->quantity, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($purchase->unit_price, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($purchase->total_cost, 2) }}</td>
                            <td class="px-4 py-3">{{ $purchase->purchase_date }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('fuel_purchase.show', $purchase) }}" class="btn-view">View</a>
                                <a href="{{ route('fuel_purchase.edit', $purchase) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('fuel_purchase.destroy', $purchase) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this purchase?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-3 text-gray-500">No purchases found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">{{ $purchases->links() }}</div>
    </div>
</x-layouts.app>
