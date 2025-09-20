<x-layouts.app :title="__('Add Pump Record')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Add Pump Record</h1>
            <a href="{{ route('pump-records.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pump-records.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="pump_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Pump <span class="text-red-500">*</span>
                </label>
                <select name="pump_id" id="pump_id" required
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                    <option value="">Select pump</option>
                    @foreach ($pumps as $pump)
                        <option value="{{ $pump->id }}"
                                data-current-meter="{{ $pump->currentMeterReading->current_meter_reading ?? 0 }}"
                                data-price="{{ $pump->fuel->price_per_litre ?? 0 }}"
                                {{ old('pump_id') == $pump->id ? 'selected' : '' }}>
                            {{ $pump->name }} ({{ $pump->fuel->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="record_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Record Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="record_date" id="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="opening_meter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Opening Meter (Auto)
                        <span class="text-xs text-gray-400 dark:text-gray-500">(from previous reading)</span>
                    </label>
                    <input type="number" step="0.01" name="opening_meter" id="opening_meter"
                           readonly
                           value="{{ old('opening_meter') }}"
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
                </div>
                <div>
                    <label for="closing_meter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1 flex justify-between items-center">
                        <span>Closing Meter <span class="text-red-500">*</span></span>
                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Must be higher than opening meter</span>
                    </label>
                    <input type="number" step="0.01" name="closing_meter" id="closing_meter" value="{{ old('closing_meter') }}" required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price_per_litre" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Price Per Litre (Auto)
                    </label>
                    <input type="number" step="0.01" name="price_per_litre" id="price_per_litre"
                           readonly
                           value="{{ old('price_per_litre') }}"
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
                </div>
                <div>
                    <label for="total_sales" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Total Sales (Auto)
                    </label>
                    <input type="number" step="0.01" name="total_sales" id="total_sales"
                           readonly
                           value="{{ old('total_sales', '0.00') }}"
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
                </div>
            </div>

            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Staff</label>
                <select name="staff_id" id="staff_id"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                    <option value="">-- Optional --</option>
                    @foreach ($staff as $person)
                        <option value="{{ $person->id }}" {{ old('staff_id') == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-md shadow-sm transition">
                    Save Record
                </button>
            </div>
        </form>
    </div>

    <script>
        function updatePumpDetails() {
            const pumpSelect = document.getElementById('pump_id');
            const selected = pumpSelect.options[pumpSelect.selectedIndex];

            const currentMeter = selected.getAttribute('data-current-meter') || 0;
            const price = selected.getAttribute('data-price') || 0;

            document.getElementById('opening_meter').value = parseFloat(currentMeter).toFixed(2);
            document.getElementById('price_per_litre').value = parseFloat(price).toFixed(2);

            calculateTotalSales();
        }

        function calculateTotalSales() {
            const openingMeter = parseFloat(document.getElementById('opening_meter').value) || 0;
            const closingMeter = parseFloat(document.getElementById('closing_meter').value) || 0;
            const pricePerLitre = parseFloat(document.getElementById('price_per_litre').value) || 0;

            const litresSold = closingMeter - openingMeter;
            const totalSales = litresSold > 0 ? litresSold * pricePerLitre : 0;

            document.getElementById('total_sales').value = totalSales.toFixed(2);
        }

        document.getElementById('pump_id').addEventListener('change', updatePumpDetails);
        document.getElementById('closing_meter').addEventListener('input', calculateTotalSales);

        window.addEventListener('DOMContentLoaded', updatePumpDetails);
    </script>
</x-layouts.app>
