<x-layouts.app :title="__('Edit Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Account</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">Update the account details below.</p>
        </div>

        <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    required
                    value="{{ old('code', $account->code) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    value="{{ old('name', $account->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Type</label>
                <select
                    id="type"
                    name="type"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                    @foreach (['Asset', 'Liability', 'Income', 'Expense', 'Equity'] as $type)
                        <option value="{{ $type }}" {{ old('type', $account->type) === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Account -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Account</label>
                <select
                    id="parent_id"
                    name="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                    <option value="">None</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->code }} - {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Balance -->
            <div>
                <label for="current_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Balance</label>
                <input
                    id="current_balance"
                    name="current_balance"
                    type="number"
                    step="0.01"
                    required
                    value="{{ old('current_balance', $account->current_balance) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('current_balance')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >{{ old('description', $account->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input
                    id="is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-indigo-600"
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Active
                </label>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900"
                >
                    Update Account
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
