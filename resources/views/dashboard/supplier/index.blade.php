<x-layouts.app :title="__('Suppliers')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Supplier Records</h2>
            <a href="{{ route('supplier.create') }}"
            class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold text-sm px-4 py-2 rounded shadow transition">
                + Add Supplier
            </a>

        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-3 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Company</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Phone</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Email</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($suppliers as $index => $supplier)
                        <tr>
                            <td class="px-4 py-3">{{ $suppliers->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $supplier->name }}</td>
                            <td class="px-4 py-3">{{ $supplier->company ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex flex-col items-end space-y-2">
                                    <a href="{{ route('supplier.show', $supplier) }}"
                                    class="inline-block bg-amber-400 text-black text-sm font-medium px-3 py-1 rounded hover:bg-amber-500 transition">
                                        View
                                    </a>

                                    <a href="{{ route('supplier.edit', $supplier) }}"
                                    class="inline-block bg-blue-400 text-white text-sm font-medium px-3 py-1 rounded hover:bg-blue-500 transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('supplier.destroy', $supplier) }}"
                                        method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Delete this supplier?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 text-white text-sm font-medium px-3 py-1 rounded hover:bg-red-600 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">No suppliers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">{{ $suppliers->links() }}</div>
    </div>
</x-layouts.app>
