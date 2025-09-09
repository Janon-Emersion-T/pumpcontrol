<x-layouts.app :title="__('Expense Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Expense Details</h2>
            <a href="{{ route('expenses.index') }}"
               class="inline-flex items-center rounded-md bg-gray-200 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 text-sm text-gray-800 dark:text-gray-100">
            <div>
                <p class="font-semibold">Date:</p>
                <p>{{ optional($expense->date)->format('Y-m-d') ?? '-' }}</p>
            </div>

            <div>
                <p class="font-semibold">Amount:</p>
                <p>{{ number_format($expense->amount, 2) }}</p>
            </div>

            <div>
                <p class="font-semibold">Account:</p>
                <p>{{ $expense->account->code }} - {{ $expense->account->name }}</p>
            </div>

            <div>
                <p class="font-semibold">Reference:</p>
                <p>{{ $expense->reference ?? '-' }}</p>
            </div>

            <div>
                <p class="font-semibold">Added By:</p>
                <p>{{ $expense->user->name ?? '-' }}</p>
            </div>

            <div class="sm:col-span-2">
                <p class="font-semibold">Description:</p>
                <p class="whitespace-pre-line">{{ $expense->description ?? '-' }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
