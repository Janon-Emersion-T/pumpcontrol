<x-layouts.app :title="__('Fuel Purchase Details')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Purchase Details</h2>
            <a href="{{ route('fuel_purchase.index') }}" class="btn-secondary">← Back</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-300">
            <div><strong>Fuel:</strong> {{ $purchase->fuel->name }}</div>
            <div><strong>Quantity:</strong> {{ number_format($purchase->quantity, 2) }} L</div>
            <div><strong>Unit Price:</strong> {{ number_format($purchase->unit_price, 2) }}</div>
            <div><strong>Total Cost:</strong> {{ number_format($purchase->total_cost, 2) }}</div>
            <div><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</div>
            <div><strong>Supplier:</strong> {{ $purchase->supplier_name ?? '-' }}</div>
            <div><strong>Reference:</strong> {{ $purchase->reference ?? '-' }}</div>
            <div><strong>Notes:</strong> {{ $purchase->notes ?? '-' }}</div>
            <div><strong>Logged By:</strong> {{ $purchase->user->name ?? '-' }}</div>
        </div>
    </div>
</x-layouts.app>
