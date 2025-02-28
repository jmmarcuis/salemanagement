<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class DeleteTaskModal extends Component
{
    public $isOpen = false;
    public $todoId = null;
    public $todoTitle = '';
    
    protected $listeners = ['openDeleteTaskModal' => 'show'];
    
    public function show($id)
    {
        $todo = Todo::find($id);
        if ($todo && $todo->user_id === Auth::id()) {
            $this->todoId = $id;
            $this->todoTitle = $todo->title;
            $this->isOpen = true;
        }
    }
    
    public function close()
    {
        $this->reset(['isOpen', 'todoId', 'todoTitle']);
    }
    
    public function destroy()
    {
        $todo = Todo::find($this->todoId);
        if ($todo && $todo->user_id === Auth::id()) {
            $todo->delete();
            $this->close();
            $this->dispatch('taskDeleted');
        }
    }
    
    public function render()
    {
        return view('livewire.delete-task-modal');
    }
}