<x-layouts.app :title="__('New Transfer')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ __('New Account Transfer') }}
            </h1>
            <a href="{{ route('account-transfers.index') }}"
               class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                &larr; {{ __('Back to Transfers') }}
            </a>
        </div>

        <form method="POST" action="{{ route('account-transfers.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- From Account -->
                <div>
                    <label for="from_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('From Account') }}
                    </label>
                    <select id="from_account_id" name="from_account_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">{{ __('Select account') }}</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected(old('from_account_id') == $account->id)>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('from_account_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- To Account -->
                <div>
                    <label for="to_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('To Account') }}
                    </label>
                    <select id="to_account_id" name="to_account_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">{{ __('Select account') }}</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected(old('to_account_id') == $account->id)>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('to_account_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Amount') }}
                </label>
                <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                       value="{{ old('amount') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Description (optional)') }}
                </label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                    {{ __('Transfer Funds') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
