<div>
    @if ($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-90">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between">
                    <h2 class="text-xl font-bold text-black">Add a Task</h2>
                    <button wire:click="close" class="font-bold mb-4 text-black">X</button>
                </div>

                <form wire:submit="store">
                    <div class="mt-2 mb-10">
                        <h4 class="text-gray-800">Task Title</h4>
                        <input wire:model="title"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                            type="text">


                        <h4 class="text-gray-800 mt-4">Description</h4>
                        <textarea wire:model="description"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                            rows="4"></textarea>

                    </div>
                    <div>
                        <button type="button" wire:click="close"
                            class="bg-red-700 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                            Close
                        </button>
                        <button type="submit"
                            class="bg-green-700 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                            Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
