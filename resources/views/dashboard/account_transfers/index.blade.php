<x-layouts.app :title="__('Account Transfers')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ __('Account Transfers') }}
            </h1>
            <a href="{{ route('account-transfers.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                {{ __('New Transfer') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('Date') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('From') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('To') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('Amount') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('By') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            {{ __('Description') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($transfers as $transfer)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $transfer->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $transfer->fromAccount->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $transfer->toAccount->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-green-600 dark:text-green-400">
                                {{ number_format($transfer->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $transfer->user->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $transfer->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('No transfers found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transfers->links() }}
        </div>
    </div>
</x-layouts.app>
