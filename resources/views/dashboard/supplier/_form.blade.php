<div class="space-y-6">

    <!-- Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $supplier->name ?? '') }}" required
               class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">
    </div>

    <!-- Company -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            Company
        </label>
        <input type="text" name="company"
               value="{{ old('company', $supplier->company ?? '') }}"
               class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">
    </div>

    <!-- Phone & Email -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Phone
            </label>
            <input type="text" name="phone"
                   value="{{ old('phone', $supplier->phone ?? '') }}"
                   class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Email
            </label>
            <input type="email" name="email"
                   value="{{ old('email', $supplier->email ?? '') }}"
                   class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">
        </div>
    </div>

    <!-- Address -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            Address
        </label>
        <textarea name="address" rows="2"
                  class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">{{ old('address', $supplier->address ?? '') }}</textarea>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            Notes
        </label>
        <textarea name="notes" rows="3"
                  class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 transition">{{ old('notes', $supplier->notes ?? '') }}</textarea>
    </div>

</div>
