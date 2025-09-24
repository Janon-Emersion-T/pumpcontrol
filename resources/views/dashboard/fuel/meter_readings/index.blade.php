<x-layouts.app :title="__('Meter Readings')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Meter Readings</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Track fuel dispensing across all pumps</p>
            </div>
            <a href="{{ route('fuel.meter-readings.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                + Add Meter Reading
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Today's Readings</h3>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $todayReadings }}</p>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">Unverified</h3>
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $unverifiedReadings }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100">Total Readings</h3>
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $meterReadings->total() }}</p>
            </div>
        </div>

        <!-- Meter Readings Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Pump</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Fuel</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Shift</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Dispensed</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($meterReadings as $reading)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $reading->reading_date->format('M j, Y') }}
                                <br>
                                <span class="text-xs text-gray-500">{{ $reading->reading_time }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $reading->pump->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $reading->fuel->name }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($reading->shift === 'morning') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($reading->shift === 'afternoon') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($reading->shift === 'evening') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @endif">
                                    {{ ucfirst($reading->shift) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ number_format($reading->total_dispensed, 3) }}L</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">Rs.{{ number_format($reading->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($reading->is_verified)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('fuel.meter-readings.show', $reading) }}"
                                   class="mr-2 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    View
                                </a>
                                <a href="{{ route('fuel.meter-readings.edit', $reading) }}"
                                   class="mr-2 inline-flex items-center rounded-md bg-yellow-500 px-3 py-1.5 text-white hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                    Edit
                                </a>
                                @if(!$reading->is_verified)
                                    <form action="{{ route('fuel.meter-readings.verify', $reading) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md bg-green-600 px-3 py-1.5 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                                            Verify
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('fuel.meter-readings.destroy', $reading) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this meter reading?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                No meter readings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $meterReadings->links() }}
        </div>
    </div>
</x-layouts.app>
