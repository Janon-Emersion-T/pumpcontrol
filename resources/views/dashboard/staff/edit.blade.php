<x-layouts.app :title="__('Edit Staff')">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Staff</h1>
            <a href="{{ route('staff.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                ← Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staff.update', $staff) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">User</label>
                <select name="user_id" required
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $staff->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}" required
                       class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}"
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Position</label>
                    <input type="text" name="position" value="{{ old('position', $staff->position) }}"
                           class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-sm">
                </div>
            </div>

<!-- Active -->
<input type="hidden" name="is_active" value="0">
<div class="flex items-center">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $staff->is_active) ? 'checked' : '' }}
           class="rounded border-gray-300 dark:border-gray-700 text-green-600 shadow-sm focus:ring-green-500">
    <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
</div>


            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-md shadow-sm transition">
                    Update Staff
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
