<x-layouts.app :title="__('Edit Pump Record')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Pump Record</h1>
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

        <form method="POST" action="{{ route('pump-records.update', $pumpRecord->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Pump
                </label>
                <input type="text" readonly
                       value="{{ $pumpRecord->pump->name }} ({{ $pumpRecord->pump->fuel->name }})"
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Record Date
                </label>
                <input type="date" name="record_date" value="{{ $pumpRecord->record_date->format('Y-m-d') }}" readonly
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="opening_meter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Opening Meter</label>
                    <input type="number" step="0.01" name="opening_meter" id="opening_meter"
                           value="{{ old('opening_meter', $pumpRecord->opening_meter) }}"
                           required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
                <div>
                    <label for="closing_meter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Closing Meter</label>
                    <input type="number" step="0.01" name="closing_meter" id="closing_meter"
                           value="{{ old('closing_meter', $pumpRecord->closing_meter) }}"
                           required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
            </div>

            <div>
                <label for="price_per_litre" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Price Per Litre
                </label>
                <input type="number" step="0.01" name="price_per_litre" id="price_per_litre"
                       value="{{ old('price_per_litre', $pumpRecord->price_per_litre) }}"
                       required
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
            </div>

            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Staff</label>
                <select name="staff_id" id="staff_id"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                    <option value="">-- Optional --</option>
                    @foreach ($staff as $person)
                        <option value="{{ $person->id }}"
                            {{ old('staff_id', $pumpRecord->staff_id) == $person->id ? 'selected' : '' }}>
                            {{ $person->first_name }} {{ $person->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-md shadow-sm transition">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
