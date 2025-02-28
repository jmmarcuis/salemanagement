<div>
    @if ($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-90 z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
                <div class="flex justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Delete Product</h2>
                    <button wire:click="close" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-4 mb-6">
                    <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this product?</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-2">"{{ $productName }}"</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button wire:click="close"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        Cancel
                    </button>
                    <button wire:click="destroy"
                        class="bg-red-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>