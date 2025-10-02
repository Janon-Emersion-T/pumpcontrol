<x-layouts.app :title="__('Pump Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pump Details</h2>
            <a href="{{ route('pump.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white hover:bg-gray-700 dark:ring-offset-gray-900">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <span class="font-semibold">Name:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $pump->name }}</div>
            </div>

            <div>
                <span class="font-semibold">Fuel Type:</span>
                <div class="mt-1 text-gray-900 dark:text-white">{{ $pump->fuel->name ?? '-' }}</div>
            </div>

            <div>
                <span class="font-semibold">Status:</span>
                <div class="mt-1">
                    @if ($pump->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 text-xs">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 text-xs">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            <a href="{{ route('pump.edit', $pump) }}"
               class="inline-flex items-center rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                Edit
            </a>
            <form action="{{ route('pump.destroy', $pump) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this pump?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
