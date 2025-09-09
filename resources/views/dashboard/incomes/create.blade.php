<x-layouts.app :title="__('Add Income')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Income</h2>
            <a href="{{ route('incomes.index') }}"
               class="inline-flex items-center rounded-md bg-gray-200 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                ← Back to List
            </a>
        </div>

        <form method="POST" action="{{ route('incomes.store') }}" class="space-y-6">
            @csrf

            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    required
                    value="{{ old('date', now()->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account -->
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account</label>
                <select
                    id="account_id"
                    name="account_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                    <option value="">-- Select Account --</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                <input
                    type="number"
                    id="amount"
                    name="amount"
                    step="0.01"
                    required
                    value="{{ old('amount') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reference -->
            <div>
                <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference</label>
                <input
                    type="text"
                    id="reference"
                    name="reference"
                    value="{{ old('reference') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm sm:text-sm"
                >
                @error('reference')
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
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    Save Income
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
