<x-layouts.app :title="__('Add Fuel Adjustment')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Add Fuel Adjustment</h2>
            <a href="{{ route('fuel-adjustments.index') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition">
                ← Back to List
            </a>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('fuel-adjustments.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">
                    Pump <span class="text-red-500">*</span>
                </label>
                <select name="pump_id" id="pump_id" required onchange="fetchFuel()"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">Select a pump</option>
                    @foreach($pumps as $pump)
                        <option value="{{ $pump->id }}">{{ $pump->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Fuel Type</label>
                <input type="text" id="fuel_name"
                       class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white"
                       readonly>
                <input type="hidden" name="fuel_id" id="fuel_id">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Liters</label>
                <input type="number" step="0.01" name="liters" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Adjustment Type</label>
                <select name="type" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="loss">Loss</option>
                    <option value="gain">Gain</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Reason</label>
                <textarea name="reason" rows="2"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-white">Adjusted At</label>
                <input type="date" name="adjusted_at" value="{{ date('Y-m-d') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-md transition">
                    Save Adjustment
                </button>
            </div>
        </form>
    </div>

    <!-- Script -->
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
