<div>
    @if ($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-90">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between">
                    <h2 class="text-xl font-bold text-black">Delete Task</h2>
                    <button wire:click="close" class="font-bold mb-4 text-black">X</button>
                </div>
                <div class="mt-4 mb-6">
                    <p class="text-gray-800">Are you sure you want to delete this task?</p>
                    <p class="font-medium text-gray-900 mt-2">"{{ $todoTitle }}"</p>
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