<x-layouts.app :title="__('Supplier Details')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Supplier Details</h2>
            <a href="{{ route('supplier.index') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition">
                ← Back to List
            </a>
        </div>

        <!-- Supplier Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <span class="font-semibold">Name:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $supplier->name }}</div>
            </div>

            <div>
                <span class="font-semibold">Company:</span>
                <div class="mt-1">{{ $supplier->company ?? '-' }}</div>
            </div>

            <div>
                <span class="font-semibold">Phone:</span>
                <div class="mt-1">{{ $supplier->phone ?? '-' }}</div>
            </div>

            <div>
                <span class="font-semibold">Email:</span>
                <div class="mt-1">{{ $supplier->email ?? '-' }}</div>
            </div>

            <div class="md:col-span-2">
                <span class="font-semibold">Address:</span>
                <div class="mt-1">{{ $supplier->address ?? '-' }}</div>
            </div>

            <div class="md:col-span-2">
                <span class="font-semibold">Notes:</span>
                <div class="mt-1">{{ $supplier->notes ?? '-' }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-6 flex justify-end gap-3">
            <a href="{{ route('supplier.edit', $supplier) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 rounded transition">
                Edit
            </a>

            <form action="{{ route('supplier.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
