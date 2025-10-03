<x-layouts.app :title="__('Edit Meter Reading')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Meter Reading</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $meterReading->pump->name }} - {{ $meterReading->reading_date->format('M j, Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('fuel.meter-readings.show', $meterReading) }}"
                   class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    ← Back to Details
                </a>
            </div>
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
        <form action="{{ route('fuel.meter-readings.update', $meterReading) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pump -->
                <div>
                    <label for="pump_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pump</label>
                    <select name="pump_id" id="pump_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select a pump</option>
                        @foreach($pumps as $pump)
                            <option value="{{ $pump->id }}" data-fuel-id="{{ $pump->fuel_id }}"
                                {{ old('pump_id', $meterReading->pump_id) == $pump->id ? 'selected' : '' }}>
                                {{ $pump->name }} ({{ $pump->fuel->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fuel -->
                <div>
                    <label for="fuel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fuel</label>
                    <select name="fuel_id" id="fuel_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select fuel</option>
                        @foreach($fuels as $fuel)
                            <option value="{{ $fuel->id }}"
                                {{ old('fuel_id', $meterReading->fuel_id) == $fuel->id ? 'selected' : '' }}>
                                {{ $fuel->name }} (Rs.{{ number_format($fuel->price_per_litre, 2) }}/L)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Opening Reading -->
                <div>
                    <label for="opening_reading" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Reading (L)</label>
                    <input type="number" step="0.001" name="opening_reading" id="opening_reading"
                           value="{{ old('opening_reading', $meterReading->opening_reading) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Closing Reading -->
                <div>
                    <label for="closing_reading" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Closing Reading (L)</label>
                    <input type="number" step="0.001" name="closing_reading" id="closing_reading"
                           value="{{ old('closing_reading', $meterReading->closing_reading) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Price per Liter -->
                <div>
                    <label for="price_per_liter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Liter (Rs.)</label>
                    <input type="number" step="0.01" name="price_per_liter" id="price_per_liter"
                           value="{{ old('price_per_liter', $meterReading->price_per_liter) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Reading Date -->
                <div>
                    <label for="reading_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reading Date</label>
                    <input type="date" name="reading_date" id="reading_date"
                           value="{{ old('reading_date', $meterReading->reading_date->format('Y-m-d')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Reading Time -->
                <div>
                    <label for="reading_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reading Time</label>
                    <input type="time" name="reading_time" id="reading_time"
                           value="{{ old('reading_time', \Carbon\Carbon::parse($meterReading->reading_time)->format('H:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Shift -->
                <div>
                    <label for="shift" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shift</label>
                    <select name="shift" id="shift" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach(['morning','afternoon','evening','night'] as $shift)
                            <option value="{{ $shift }}" {{ old('shift', $meterReading->shift) == $shift ? 'selected' : '' }}>
                                {{ ucfirst($shift) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $meterReading->notes) }}</textarea>
            </div>

            <!-- Calculated Values -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Calculated Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Dispensed</label>
                        <p id="total_dispensed" class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ number_format($meterReading->total_dispensed, 3) }} L</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                        <p id="total_amount" class="text-lg font-semibold text-green-600 dark:text-green-400">Rs.{{ number_format($meterReading->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification Notice -->
            @if($meterReading->is_verified)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Verified Reading</h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>This reading has been verified. Changes will remove the verification status.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('fuel.meter-readings.show', $meterReading) }}"
                   class="inline-flex items-center rounded-md bg-gray-300 px-4 py-2 text-gray-700 shadow-sm transition hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Update Meter Reading
                </button>
            </div>
        </form>
    </div>

    <!-- JS -->
    <script>
        function calculateTotals() {
            const opening = parseFloat(document.getElementById('opening_reading').value) || 0;
            const closing = parseFloat(document.getElementById('closing_reading').value) || 0;
            const price = parseFloat(document.getElementById('price_per_liter').value) || 0;

            const dispensed = Math.max(0, closing - opening);
            const amount = dispensed * price;

            document.getElementById('total_dispensed').textContent = dispensed.toFixed(3) + ' L';
            document.getElementById('total_amount').textContent = 'Rs.' + amount.toFixed(2);
        }

        function updatePriceFromFuel() {
            const fuelSelect = document.getElementById('fuel_id');
            const selectedOption = fuelSelect.options[fuelSelect.selectedIndex];
            const priceMatch = selectedOption?.text.match(/Rs.([\d.]+)/);
            if (priceMatch) document.getElementById('price_per_liter').value = priceMatch[1];
            calculateTotals();
        }

        document.getElementById('pump_id').addEventListener('change', function() {
            const fuelId = this.selectedOptions[0].dataset.fuelId;
            if (fuelId) document.getElementById('fuel_id').value = fuelId;
            updatePriceFromFuel();
        });

        ['opening_reading', 'closing_reading', 'price_per_liter'].forEach(id =>
            document.getElementById(id).addEventListener('input', calculateTotals)
        );
        document.getElementById('fuel_id').addEventListener('change', updatePriceFromFuel);

        window.addEventListener('DOMContentLoaded', calculateTotals);
    </script>
</x-layouts.app>
