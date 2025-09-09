<x-layouts.app :title="__('Pumps')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pump Records</h2>
            <a href="{{ route('pump.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                + Add Pump
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Fuel Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse ($pumps as $index => $pump)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $pumps->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $pump->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $pump->fuel->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                @if ($pump->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 text-xs">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 text-xs">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('pump.show', $pump) }}"
                                   class="mr-2 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    View
                                </a>
                                <a href="{{ route('pump.edit', $pump) }}"
                                   class="mr-2 inline-flex items-center rounded-md bg-yellow-500 px-3 py-1.5 text-white hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                    Edit
                                </a>
                                <form action="{{ route('pump.destroy', $pump) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this pump?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                No pump records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $pumps->links() }}
        </div>
    </div>
</x-layouts.app>
