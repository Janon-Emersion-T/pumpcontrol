<x-layouts.app :title="__('Dashboard')">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Fuel Availability Dashboard</h1>

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
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Available Fuel:</span>
                            {{ number_format(optional($pump->currentFuel)->current_fuel, 2) }} Liters
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
