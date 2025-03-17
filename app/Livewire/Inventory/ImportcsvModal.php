<?php

namespace App\Livewire\Inventory;

use Livewire\Component;

class ImportcsvModal extends Component
{
    public $show = false;

    protected $listeners = ['openImportModal' => 'show'];

    public function show()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->dispatch('closeImportModal');
    }

    public function render()
    {
        return view('livewire.inventory.importcsv-modal');
    }
}
