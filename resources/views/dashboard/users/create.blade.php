<x-layouts.app :title="__('Create User')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New User</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">Fill in the details below to create a new user and assign roles.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    autofocus
                    value="{{ old('name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Roles -->
            <div>
                <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Roles</label>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    @foreach ($roles as $role)
                        <label class="inline-flex items-center space-x-2 text-sm text-gray-800 dark:text-gray-100">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-indigo-600"
                            >
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900"
                >
                    Create User
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
