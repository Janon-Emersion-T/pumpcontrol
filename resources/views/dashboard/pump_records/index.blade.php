<x-layouts.app :title="__('Pump Records')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 space-y-6">
        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Pump Records</h1>
            <a href="{{ route('pump-records.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded shadow-sm transition">
                + Add Record
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Pump</th>
                        <th class="px-4 py-2">Litres Sold</th>
                        <th class="px-4 py-2">Total Sales</th>
                        <th class="px-4 py-2">Staff</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr class="border-t dark:border-gray-700">
                            <td class="px-4 py-2">{{ $record->record_date }}</td>
                            <td class="px-4 py-2">{{ $record->pump->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $record->litres_sold }}</td>
                            <td class="px-4 py-2">{{ number_format($record->total_sales, 2) }}</td>
                            <td class="px-4 py-2">{{ $record->staff->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('pump-records.show', $record) }}" class="text-blue-600 hover:underline">Show</a>
                                <a href="{{ route('pump-records.edit', $record) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('pump-records.destroy', $record) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $records->links() }}
        </div>
    </div>
</x-layouts.app>
