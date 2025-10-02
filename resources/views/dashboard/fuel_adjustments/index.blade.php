<x-layouts.app :title="__('Fuel Adjustments')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Fuel Adjustments</h2>
            <a href="{{ route('fuel-adjustments.create') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded transition">
                + Add Adjustment
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-3 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Pump</th>
                        <th class="px-4 py-2 text-left">Fuel</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Liters</th>
                        <th class="px-4 py-2 text-left">Reason</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                    @forelse ($adjustments as $adjustment)
                        <tr>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($adjustment->adjusted_at)->format('d M Y') }}</td>
                            <td class="px-4 py-2">{{ $adjustment->pump->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $adjustment->fuel->name ?? '-' }}</td>
                            <td class="px-4 py-2 capitalize">
                                <span class="{{ $adjustment->type === 'gain' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $adjustment->type }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ number_format($adjustment->liters, 2) }}</td>
                            <td class="px-4 py-2">{{ $adjustment->reason ?? '-' }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('fuel-adjustments.show', $adjustment) }}"
                                   class="inline-block px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                                    View
                                </a>
                                <a href="{{ route('fuel-adjustments.edit', $adjustment) }}"
                                   class="inline-block px-2 py-1 text-xs bg-amber-500 text-white rounded hover:bg-amber-600">
                                    Edit
                                </a>
                                <form action="{{ route('fuel-adjustments.destroy', $adjustment) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this adjustment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-block px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                No adjustments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $adjustments->links() }}
        </div>
    </div>
</x-layouts.app>
