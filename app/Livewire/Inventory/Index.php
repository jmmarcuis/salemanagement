<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Method to handle the add product button click
    public function addProduct()
    {
        // Redirect to the inventory/addproduct route
        return redirect()->route('inventory.addproduct');
    }

    // Method to handle the edit product button click
    public function editProduct($id)
    {
        // Redirect to the inventory/edit route with the product ID
        return redirect()->route('inventory.edit', ['id' => $id]);
    }

    // Method to handle delete product click
    public function deleteProduct($id)
    {
        // Get product details for confirmation message
        $product = Product::find($id);

        if ($product) {
            // Dispatch event to show delete confirmation
            $this->dispatch('swal:confirm', [
                'title' => 'Are you sure?',
                'text' => "Do you want to delete product: {$product->name}?",
                'icon' => 'warning',
                'confirmButtonText' => 'Yes, delete it!',
                'cancelButtonText' => 'Cancel',
                'productId' => $id
            ]);
        }
    }


    // Method to handle actual deletion
    public function confirmDelete($id)
    {
        $product = Product::find($id);

        if ($product) {
            $productName = $product->name;
            $product->delete();

            // Show success message
            $this->dispatch('swal:success', [
                'title' => 'Deleted!',
                'text' => "Product '{$productName}' has been deleted.",
                'icon' => 'success',
            ]);

            // Refresh the component
            $this->dispatch('productDeleted');
        }
    }


    // Reset pagination when search is updated
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add listener for product deleted event
    protected $listeners = ['productDeleted' => '$refresh'];

    public function render()
    {
        // Get products with search filter
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orWhere('product_type', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.inventory.index', [
            'products' => $products
        ])->layout('layouts.app');
    }
}
