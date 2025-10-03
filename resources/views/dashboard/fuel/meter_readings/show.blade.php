<x-layouts.app :title="__('Meter Reading Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Meter Reading Details</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $meterReading->pump->name }} - {{ $meterReading->reading_date->format('M j, Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('fuel.meter-readings.index') }}"
                   class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    ← Back to Readings
                </a>
                <a href="{{ route('fuel.meter-readings.edit', $meterReading) }}"
                   class="inline-flex items-center rounded-md bg-yellow-500 px-4 py-2 text-white shadow-sm transition hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    Edit Reading
                </a>
            </div>
        </div>

        <!-- Grid Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Basic Information -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pump</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->pump->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fuel Type</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->fuel->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reading Date</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->reading_date->format('l, F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reading Time</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($meterReading->reading_time)->format('g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Shift</dt>
                        <dd class="text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($meterReading->shift === 'morning') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($meterReading->shift === 'afternoon') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($meterReading->shift === 'evening') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @endif">
                                {{ ucfirst($meterReading->shift) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recorded By</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->user->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Meter Readings -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Meter Readings</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Opening Reading</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($meterReading->opening_reading, 3) }} L</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Closing Reading</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($meterReading->closing_reading, 3) }} L</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dispensed</dt>
                        <dd class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($meterReading->total_dispensed, 3) }} L</dd>
                    </div>
                </dl>
            </div>

            <!-- Financial Information -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Financial Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price per Liter</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">Rs.{{ number_format($meterReading->price_per_liter, 3) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                        <dd class="text-xl font-bold text-green-600 dark:text-green-400">Rs.{{ number_format($meterReading->total_amount, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Verification Status -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Verification Status</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="text-sm">
                            @if($meterReading->is_verified)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                    ✓ Verified
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                    ⚠ Unverified
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if($meterReading->is_verified)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Verified By</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->verifiedBy->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Verified At</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->verified_at?->format('M j, Y g:i A') ?? 'N/A' }}</dd>
                        </div>
                    @else
                        <div class="pt-2">
                            <form action="{{ route('fuel.meter-readings.verify', $meterReading) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Verify Reading
                                </button>
                            </form>
                        </div>
                    @endif
                </dl>
            </div>

        </div>

        <!-- Notes Section -->
        @if($meterReading->notes)
            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Notes</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $meterReading->notes }}</p>
            </div>
        @endif

        <!-- Record Timestamps -->
        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Record Information</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $meterReading->updated_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

    </div>
</x-layouts.app>
