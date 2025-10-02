<x-layouts.app :title="__('Role Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Role Details</h2>
            <a href="{{ route('roles.index') }}"
               class="inline-flex items-center rounded-md bg-gray-200 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Role Name -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Role Name</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $role->name }}</p>
            </div>

            <!-- Permissions -->
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned Permissions</h3>
                <div class="mt-2 flex flex-wrap gap-2">
                    @forelse ($role->permissions->where('name', '!=', 'god') as $permission)
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-800 dark:text-indigo-200">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-500 dark:text-gray-400">No permissions assigned.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
