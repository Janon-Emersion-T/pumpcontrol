<x-layouts.app :title="__('Pump Record Details')">
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-900 shadow rounded-xl space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Pump Record Details</h1>
            <a href="{{ route('pump-records.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-800 dark:text-gray-200">
            <div>
                <span class="font-semibold">Pump:</span><br>
                {{ $pumpRecord->pump->name }} ({{ $pumpRecord->pump->fuel->name }})
            </div>

            <div>
                <span class="font-semibold">Record Date:</span><br>
                {{ \Carbon\Carbon::parse($pumpRecord->record_date)->format('F d, Y') }}
            </div>

            <div>
                <span class="font-semibold">Opening Meter:</span><br>
                {{ number_format($pumpRecord->opening_meter, 2) }} L
            </div>

            <div>
                <span class="font-semibold">Closing Meter:</span><br>
                {{ number_format($pumpRecord->closing_meter, 2) }} L
            </div>

            <div>
                <span class="font-semibold">Litres Sold:</span><br>
                {{ number_format($pumpRecord->litres_sold, 2) }} L
            </div>

            <div>
                <span class="font-semibold">Price per Litre:</span><br>
                {{ number_format($pumpRecord->price_per_litre, 2) }} {{ config('app.currency', 'ETB') }}
            </div>

            <div>
                <span class="font-semibold">Total Sales:</span><br>
                {{ number_format($pumpRecord->total_sales, 2) }} {{ config('app.currency', 'ETB') }}
            </div>

            <div>
                <span class="font-semibold">Staff:</span><br>
                {{ $pumpRecord->staff?->full_name ?? 'N/A' }}
            </div>
        </div>

        <div class="pt-6">
            <a href="{{ route('pump-records.edit', $pumpRecord->id) }}"
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                Edit Record
            </a>
        </div>
    </div>
</x-layouts.app>
