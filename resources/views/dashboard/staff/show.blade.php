<!-- resources/views/dashboard/staff/show.blade.php -->

<x-layouts.app :title="__('View Staff')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">View Staff</h1>
            <a href="{{ route('staff.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        <div class="text-sm space-y-4 text-gray-800 dark:text-white">
            <div>
                <strong>Name:</strong> {{ $staff->first_name }} {{ $staff->last_name }}
            </div>

            <div>
                <strong>Email:</strong> {{ $staff->email }}
            </div>

            <div>
                <strong>Phone:</strong> {{ $staff->phone ?? 'N/A' }}
            </div>

            <div>
                <strong>Position:</strong> {{ $staff->position ?? 'N/A' }}
            </div>

            <div>
                <strong>Status:</strong>
                <span class="font-semibold {{ $staff->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $staff->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if ($staff->user)
                <div>
                    <strong>Linked User:</strong> {{ $staff->user->name }}
                </div>
            @endif
        </div>

        <div class="pt-6">
            <a href="{{ route('staff.edit', $staff) }}"
               class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md shadow-sm">
                Edit Staff
            </a>
        </div>

    </div>
</x-layouts.app>
