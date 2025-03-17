<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSearchModal extends Component
{
    use WithPagination;
    
    public $searchTerm = '';
    public $showModal = false;
    
    protected $listeners = ['openProductSearchModal' => 'openModal'];
    
    public function openModal()
    {
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function selectProduct(Product $product)
    {
        $this->dispatch('productSelected', product: $product);
        $this->closeModal();
    }
    
    public function render()
    {
        $products = Product::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('code', 'like', '%' . $this->searchTerm . '%');
            })
            ->where('available', true)
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->paginate(10);
            
        return view('livewire.product-search-modal', [
            'products' => $products,
            'showModal' => $this->showModal,
        ])->layout('layouts.app');
    }
}