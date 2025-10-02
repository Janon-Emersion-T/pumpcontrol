<x-layouts.app :title="__('Fuel Adjustment Details')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Fuel Adjustment Details</h2>
            <a href="{{ route('fuel-adjustments.index') }}"
               class="btn-secondary">← Back to List</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <span class="font-semibold">Pump:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $fuelAdjustment->pump->name }}</div>
            </div>

            <div>
                <span class="font-semibold">Fuel Type:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $fuelAdjustment->fuel->name }}</div>
            </div>

            <div>
                <span class="font-semibold">Liters:</span>
                <div class="mt-1">{{ number_format($fuelAdjustment->liters, 2) }}</div>
            </div>

            <div>
                <span class="font-semibold">Type:</span>
                <div class="mt-1 capitalize">{{ $fuelAdjustment->type }}</div>
            </div>

            <div>
                <span class="font-semibold">Adjusted At:</span>
                <div class="mt-1">{{ $fuelAdjustment->adjusted_at->format('Y-m-d') }}</div>
            </div>

            <div>
                <span class="font-semibold">Entered By:</span>
                <div class="mt-1">{{ $fuelAdjustment->user->name ?? 'N/A' }}</div>
            </div>

            <div class="md:col-span-2">
                <span class="font-semibold">Reason:</span>
                <div class="mt-1">{{ $fuelAdjustment->reason ?? '-' }}</div>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            <a href="{{ route('fuel-adjustments.edit', $fuelAdjustment) }}" class="btn-edit">Edit</a>
            <form action="{{ route('fuel-adjustments.destroy', $fuelAdjustment) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this adjustment?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.app>
