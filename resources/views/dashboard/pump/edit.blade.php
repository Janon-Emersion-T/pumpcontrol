<x-layouts.app :title="__('Edit Pump')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pump</h2>
            <a href="{{ route('pump.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white hover:bg-gray-700 dark:ring-offset-gray-900">
                ← Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-800 dark:text-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pump.update', $pump) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pump Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $pump->name) }}" required class="input-field">
            </div>

            <div>
                <label for="fuel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fuel Type</label>
                <select name="fuel_id" id="fuel_id" required class="input-field">
                    @foreach ($fuels as $fuel)
                        <option value="{{ $fuel->id }}" @selected(old('fuel_id', $pump->fuel_id) == $fuel->id)>
                            {{ $fuel->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                        class="rounded text-indigo-600 border-gray-300 dark:bg-gray-800"
                        @checked(old('is_active', $pump->is_active))>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>

            <div>
                <button type="submit" class="btn-primary">Update Pump</button>
            </div>
        </form>
    </div>
</x-layouts.app>
