<x-layouts.app :title="__('Roles')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Role Management</h2>

            @can('create users')
                <a href="{{ route('roles.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    + Create Role
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @can('read users')
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Role Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Permissions</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                        @forelse ($roles as $index => $role)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $roles->firstItem() + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $role->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @forelse ($role->permissions as $permission)
                                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-800 dark:text-indigo-200">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 dark:text-gray-400">No permissions</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 text-right text-sm">
                                    @can('read users')
                                        <a href="{{ route('roles.show', $role) }}"
                                           class="mr-2 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                            Show
                                        </a>
                                    @endcan

                                    @can('update users')
                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="mr-2 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete users')
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $roles->links() }}
            </div>
        @else
            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                You do not have permission to view roles.
            </div>
        @endcan
    </div>
</x-layouts.app>
