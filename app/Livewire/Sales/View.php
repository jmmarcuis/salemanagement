<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;

class View extends Component
{
    public $sale;
    public $saleId;

    public function mount($id)
    {
        $this->saleId = $id;
        $this->loadSale();
    }

    public function loadSale()
    {
        $this->sale = Sale::with(['customer', 'user', 'items.product', 'discounts'])
            ->findOrFail($this->saleId);
    }

    public function render()
    {
        return view('livewire.sales.view')->layout('layouts.app');
    }
}