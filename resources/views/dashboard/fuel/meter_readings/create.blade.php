<x-layouts.app :title="__('Add Meter Reading')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Meter Reading</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Record fuel dispensed from a pump</p>
            </div>
            <a href="{{ route('fuel.meter-readings.index') }}"
                class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to Readings
            </a>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-800 dark:text-red-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('fuel.meter-readings.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Pump Selection -->
                <div>
                    <label for="pump_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pump</label>
                    <select name="pump_id" id="pump_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select a pump</option>
                        @foreach ($pumps as $pump)
                            <option value="{{ $pump->id }}" data-fuel-id="{{ $pump->fuel_id }}"
                                data-opening="{{ old('opening_reading', $defaultOpenings[$pump->id] ?? 0) }}"
                                {{ old('pump_id') == $pump->id ? 'selected' : '' }}>
                                {{ $pump->name }} ({{ $pump->fuel->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fuel (Auto-populated) -->
                <div>
                    <label for="fuel_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fuel</label>
                    <select name="fuel_id" id="fuel_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select fuel</option>
                        @foreach ($fuels as $fuel)
                            <option value="{{ $fuel->id }}" {{ old('fuel_id') == $fuel->id ? 'selected' : '' }}>
                                {{ $fuel->name }} (Rs.{{ number_format($fuel->price_per_litre, 2) }}/L)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Opening Reading -->
                <div>
                    <label for="opening_reading"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Reading (L)</label>
                    <input type="number" step="0.001" name="opening_reading" id="opening_reading"
                        value="{{ old('opening_reading', $defaultOpenings[$pumps->first()->id] ?? 0) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Closing Reading -->
                <div>
                    <label for="closing_reading"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Closing Reading (L)</label>
                    <input type="number" step="0.001" name="closing_reading" id="closing_reading"
                        value="{{ old('closing_reading') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Price per Liter -->
                <div>
                    <label for="price_per_liter"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Liter (Rs.)</label>
                    <input type="number" step="0.01" name="price_per_liter" id="price_per_liter"
                        value="{{ old('price_per_liter') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Reading Date -->
                <div>
                    <label for="reading_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reading
                        Date</label>
                    <input type="date" name="reading_date" id="reading_date"
                        value="{{ old('reading_date', date('Y-m-d')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Reading Time -->
                <div>
                    <label for="reading_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reading
                        Time</label>
                    <input type="time" name="reading_time" id="reading_time"
                        value="{{ old('reading_time', date('H:i')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Shift -->
                <div>
                    <label for="shift"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shift</label>
                    <select name="shift" id="shift" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="afternoon" {{ old('shift') == 'afternoon' ? 'selected' : '' }}>Afternoon
                        </option>
                        <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>Evening</option>
                        <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Night</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes
                    (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
            </div>

            <!-- Calculated Values -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Calculated Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                            Dispensed</label>
                        <p id="total_dispensed" class="text-lg font-semibold text-blue-600 dark:text-blue-400">0.000 L
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                        <p id="total_amount" class="text-lg font-semibold text-green-600 dark:text-green-400">Rs.0.00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('fuel.meter-readings.index') }}"
                    class="inline-flex items-center rounded-md bg-gray-300 px-4 py-2 text-gray-700 shadow-sm transition hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Meter Reading
                </button>
            </div>
        </form>
    </div>

    <script>
        // Calculate totals
        function calculateTotals() {
            const opening = parseFloat(document.getElementById('opening_reading').value) || 0;
            const closing = parseFloat(document.getElementById('closing_reading').value) || 0;
            const price = parseFloat(document.getElementById('price_per_liter').value) || 0;

            const dispensed = Math.max(0, closing - opening);
            const amount = dispensed * price;

            document.getElementById('total_dispensed').textContent = dispensed.toFixed(3) + ' L';
            document.getElementById('total_amount').textContent = 'Rs.' + amount.toFixed(2);
        }

        // Auto-populate fuel and opening reading when pump is selected
        document.getElementById('pump_id').addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];

            // Fuel auto-fill
            const fuelId = selectedOption.dataset.fuelId;
            if (fuelId) document.getElementById('fuel_id').value = fuelId;
            updatePriceFromFuel();

            // Opening reading auto-fill
            const defaultOpening = selectedOption.dataset.opening;
            if (defaultOpening) {
                document.getElementById('opening_reading').value = parseFloat(defaultOpening).toFixed(3);
                calculateTotals();
            }
        });

        // Update price when fuel changes
        function updatePriceFromFuel() {
            const fuelSelect = document.getElementById('fuel_id');
            const selectedOption = fuelSelect.selectedOptions[0];
            const priceMatch = selectedOption?.text.match(/Rs.([\d.]+)/);
            if (priceMatch) document.getElementById('price_per_liter').value = priceMatch[1];
            calculateTotals();
        }

        // Real-time calculation
        ['opening_reading', 'closing_reading', 'price_per_liter'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateTotals);
        });
        document.getElementById('fuel_id').addEventListener('change', updatePriceFromFuel);

        // Initialize totals
        window.addEventListener('DOMContentLoaded', calculateTotals);
    </script>
</x-layouts.app>
