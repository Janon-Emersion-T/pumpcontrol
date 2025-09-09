<x-layouts.app :title="__('Edit Role')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Role</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">Modify the role name and its assigned permissions.</p>
        </div>

        <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    value="{{ old('name', $role->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permissions</label>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    @foreach ($permissions->where('name', '!=', 'god') as $permission)
                        <label class="inline-flex items-center space-x-2 text-sm text-gray-800 dark:text-gray-100">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-indigo-600"
                            >
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900"
                >
                    Update Role
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
