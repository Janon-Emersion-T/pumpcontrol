<x-layouts.app :title="__('Add Fuel Purchase')">
    <div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Add New Fuel Purchase</h2>
            <a href="{{ route('fuel_purchase.index') }}" class="btn-secondary">← Back</a>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fuel_purchase.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('dashboard.fuel_purchase._form', ['purchase' => null])
            <button type="submit" class="btn-primary">Save Purchase</button>
        </form>
    </div>
</x-layouts.app>
