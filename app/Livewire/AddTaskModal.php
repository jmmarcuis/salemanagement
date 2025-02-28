<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class AddTaskModal extends Component
{
    public $isOpen = false;
    public $title = '';
    public $description = '';

    protected $listeners = ['openAddTaskModal' => 'show'];

    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'nullable'
    ];

    public function show()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['isOpen', 'title', 'description']);
    }

    public function store()
    {
        $this->validate();

        Todo::create([
            'title' => $this->title,
            'description' => $this->description,
            'completed' => false,
            'user_id' => Auth::id()
        ]);

        $this->close(); // Close the modal
        $this->dispatch('taskAdded'); // Dispatch the event
    }

    public function render()
    {
        return view('livewire.add-task-modal');
    }
}