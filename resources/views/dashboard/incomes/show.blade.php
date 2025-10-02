<x-layouts.app :title="__('Income Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Income Details</h2>
            <a href="{{ route('incomes.index') }}"
               class="inline-flex items-center rounded-md bg-gray-200 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 text-sm text-gray-800 dark:text-gray-100">
            <div>
                <p class="font-semibold">Date:</p>
                <p>{{ $income->date->format('Y-m-d') }}</p>
            </div>

            <div>
                <p class="font-semibold">Amount:</p>
                <p>{{ number_format($income->amount, 2) }}</p>
            </div>

            <div>
                <p class="font-semibold">Account:</p>
                <p>{{ $income->account->code }} - {{ $income->account->name }}</p>
            </div>

            <div>
                <p class="font-semibold">Reference:</p>
                <p>{{ $income->reference ?? '-' }}</p>
            </div>

            <div>
                <p class="font-semibold">Added By:</p>
                <p>{{ $income->user->name ?? '-' }}</p>
            </div>

            <div class="sm:col-span-2">
                <p class="font-semibold">Description:</p>
                <p class="whitespace-pre-line">{{ $income->description ?? '-' }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
