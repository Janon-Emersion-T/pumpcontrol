<x-layouts.app :title="__('Edit Fuel')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Fuel</h2>
            <a href="{{ route('fuel.index') }}"
               class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                ← Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-800 dark:text-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fuel.update', $fuel) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fuel Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $fuel->name) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="price_per_litre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Litre</label>
                <input type="number" name="price_per_litre" id="price_per_litre" step="0.01"
                       value="{{ old('price_per_litre', $fuel->price_per_litre) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="stock_litres" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock (Litres)</label>
                <input type="number" name="stock_litres" id="stock_litres" step="0.01"
                       value="{{ old('stock_litres', $fuel->stock_litres) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $fuel->description) }}</textarea>
            </div>

            <div>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-gray-900">
                    Update Fuel
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
