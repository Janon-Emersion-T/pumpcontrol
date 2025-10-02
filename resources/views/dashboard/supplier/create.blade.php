<x-layouts.app :title="__('Add Supplier')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Add New Supplier</h2>
            <a href="{{ route('supplier.index') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition">
                ← Back to List
            </a>
        </div>

        <!-- Error Display -->
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('supplier.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Form Fields --}}
            @include('dashboard.supplier._form', ['supplier' => null])

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                        class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-md shadow-sm transition focus:outline-none focus:ring focus:ring-green-400 focus:ring-opacity-50">
                    Save Supplier
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
