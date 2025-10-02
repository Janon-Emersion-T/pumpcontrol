<x-layouts.app :title="__('Edit Fuel Adjustment')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Fuel Adjustment</h2>
            <a href="{{ route('fuel-adjustments.index') }}" class="btn-secondary">← Back to List</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fuel-adjustments.update', $fuelAdjustment) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Pump <span class="text-red-500">*</span></label>
                <select name="pump_id" id="pump_id" required onchange="fetchFuel()"
                        class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Select a pump</option>
                    @foreach($pumps as $pump)
                        <option value="{{ $pump->id }}" {{ $fuelAdjustment->pump_id == $pump->id ? 'selected' : '' }}>
                            {{ $pump->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Fuel Type</label>
                <input type="text" id="fuel_name" class="w-full rounded border-gray-300 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-white" readonly
                       value="{{ $fuelAdjustment->fuel->name }}">
                <input type="hidden" name="fuel_id" id="fuel_id" value="{{ $fuelAdjustment->fuel_id }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Liters</label>
                <input type="number" step="0.01" name="liters" required
                       value="{{ $fuelAdjustment->liters }}"
                       class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Adjustment Type</label>
                <select name="type" required
                        class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="gain" {{ $fuelAdjustment->type === 'gain' ? 'selected' : '' }}>Gain</option>
                    <option value="loss" {{ $fuelAdjustment->type === 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Reason</label>
                <textarea name="reason" rows="2"
                          class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ $fuelAdjustment->reason }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Adjusted At</label>
                <input type="date" name="adjusted_at" value="{{ $fuelAdjustment->adjusted_at->format('Y-m-d') }}" required
                       class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded transition">
                    Update Adjustment
                </button>
            </div>
        </form>
    </div>

    <script>
        function fetchFuel() {
            const pumpId = document.getElementById('pump_id').value;

            if (!pumpId) {
                document.getElementById('fuel_name').value = '';
                document.getElementById('fuel_id').value = '';
                return;
            }

            fetch(`/pumps/${pumpId}/fuel`)
                .then(res => {
                    if (!res.ok) throw new Error("Pump or fuel not found");
                    return res.json();
                })
                .then(data => {
                    document.getElementById('fuel_name').value = data.name ?? '';
                    document.getElementById('fuel_id').value = data.id ?? '';
                })
                .catch(err => {
                    alert(err.message);
                    document.getElementById('fuel_name').value = '';
                    document.getElementById('fuel_id').value = '';
                });
        }
    </script>
</x-layouts.app>
