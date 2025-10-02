<x-layouts.app :title="__('Account Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Account Details</h2>
            <a href="{{ route('accounts.index') }}"
               class="inline-flex items-center rounded-md bg-gray-200 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Code</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $account->code }}</p>
            </div>

            <!-- Name -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $account->name }}</p>
            </div>

            <!-- Type -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $account->type }}</p>
            </div>

            <!-- Parent -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Parent Account</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">
                    {{ $account->parent?->code }} {{ $account->parent?->name ?? '-' }}
                </p>
            </div>

            <!-- Current Balance -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Balance</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">
                    {{ number_format($account->current_balance, 2) }}
                </p>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                <p class="mt-1 text-lg text-gray-900 dark:text-white">
                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                <p class="mt-1 text-base text-gray-800 dark:text-gray-100 whitespace-pre-wrap">
                    {{ $account->description ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>
