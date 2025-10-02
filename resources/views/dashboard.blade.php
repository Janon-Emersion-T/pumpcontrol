<x-layouts.app :title="__('Dashboard')">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Dashboard</h1>

        {{-- Fuel Tank Levels Section --}}
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Fuel Tank Levels</h2>
            @if($fuels->isEmpty())
                <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-md">
                    No fuel tanks available at the moment.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($fuels as $fuel)
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                            <h3 class="text-lg font-bold mb-3">{{ $fuel->name }}</h3>
                            <div class="space-y-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold">{{ number_format($fuel->stock_litres, 0) }}</span>
                                    <span class="text-sm opacity-90">Liters</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    {{ $fuel->pumps_count }} {{ Str::plural('Pump', $fuel->pumps_count) }}
                                </div>
                                <div class="text-xs opacity-75 mt-2 pt-2 border-t border-white/30">
                                    Rs. {{ number_format($fuel->price_per_litre, 2) }}/L
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pump Meter Readings Section --}}
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Pump Meter Readings</h2>
            @if($pumps->isEmpty())
                <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-md">
                    No pumps available at the moment.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($pumps as $pump)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                                {{ $pump->name ?? 'Unnamed Pump' }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                <span class="font-medium">Fuel Type:</span> {{ $pump->fuel->name ?? 'Unknown' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                <span class="font-medium">Current Meter Reading:</span>
                                {{ number_format(optional($pump->currentMeterReading)->current_meter_reading ?? 0, 2) }} Liters
                            </p>
                            @if(optional($pump->currentMeterReading)->previous_meter_reading)
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Liters Sold:</span>
                                    {{ number_format($pump->currentMeterReading->liters_sold, 2) }} Liters
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
