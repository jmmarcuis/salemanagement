<div>
    <div class="flex justify-between">
        <h2 class="text-white text-2xl">Todo App 📓</h2>

        <button
            class=" bg-gray-700 text-white flex align-middle justify-center hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
            wire:click="addTaskButton">
            <x-heroicon-o-plus class="w-5 h-5" />

            <p>Add Task</p>
        </button>
    </div>
    <ul class="mt-4">
        @foreach ($todos as $todo)
            <li class="flex justify-between items-center bg-gray-900 p-2 rounded mb-2">
                <div class="flex">
                    <span class="{{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                        {{ $todo->title }}
                    </span>
                    <button class="text-white hover:underline ml-2">
                        <x-heroicon-o-information-circle class=" text-white w-5 h-5" />

                    </button>
                </div>
                <div class="flex">
                    <button class="text-gray-600 hover:underline">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </button>

                    <button wire:click="destroy({{ $todo->id }})" class="text-red-600 hover:underline">
                        <x-heroicon-o-archive-box-x-mark class="w-5 h-5" />
                    </button>
                    <button wire:click="toggleComplete({{ $todo->id }})" class="text-green-600 hover:underline">
                        <x-heroicon-s-check class="w-5 h-5" />
                    </button>
                </div>
            </li>
        @endforeach
    </ul>

    {{-- Add Task Modal --}}
    @livewire('add-task-modal')

    {{-- Edit Task Modal --}}
    {{-- ! TO BE ADDED ! --}}

    {{-- Delete Task Modal --}}
    @livewire('delete-task-modal')
</div>
