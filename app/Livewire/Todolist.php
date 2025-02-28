<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class Todolist extends Component
{
    public $todos;

    protected $listeners = [
        'taskAdded' => 'refreshTodos',
        'taskDeleted' => 'refreshTodos'
    ];

    public function mount()
    {
        $this->refreshTodos();
    }

    public function refreshTodos()
    {
        $this->todos = Todo::where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function addTaskButton()
    {
        $this->dispatch('openAddTaskModal');
    }

    public function toggleComplete($id)
    {
        $todo = Todo::find($id);
        if ($todo && $todo->user_id === Auth::id()) {
            $todo->completed = !$todo->completed;
            $todo->save();
            $this->refreshTodos();
        }
    }

    // public function destroy($id)
    // {
    //     $todo = Todo::find($id);
    //     if ($todo && $todo->user_id === Auth::id()) {
    //         $todo->delete();
    //         $this->refreshTodos();
    //     }
    // }

    public function destroy($id)
    {
        $this->dispatch('openDeleteTaskModal', $id);
    }

    public function render()
    {
        return view('livewire.todolist');
    }
}
