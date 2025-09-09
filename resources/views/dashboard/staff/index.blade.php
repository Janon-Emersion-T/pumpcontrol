<x-layouts.app :title="__('Staff')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 space-y-6">
        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Staff</h1>
            <a href="{{ route('staff.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded shadow-sm transition">
                + Add Staff
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $person)
                        <tr class="border-t dark:border-gray-700">
                            <td class="px-4 py-2">{{ $person->first_name }} {{ $person->last_name }}</td>
                            <td class="px-4 py-2">{{ $person->email }}</td>
                            <td class="px-4 py-2">{{ $person->phone ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $person->position ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $person->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $person->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('staff.show', $person) }}" class="text-blue-600 hover:underline">Show</a>
                                <a href="{{ route('staff.edit', $person) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('staff.destroy', $person) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this staff member?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $staff->links() }}</div>
    </div>
</x-layouts.app>
