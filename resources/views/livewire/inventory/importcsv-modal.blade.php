<div x-data="{ show: @entangle('showImportModal') }">
    <div x-show="show" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Import CSV</h2>
            <p class="text-gray-600 dark:text-gray-300">Select a CSV file to import.</p>

            <div class="mt-4 flex justify-end space-x-2">
                <button @click="show = false" class="px-4 py-2 bg-gray-500 text-white rounded">Close</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Import</button>
            </div>
        </div>
    </div>
</div>
