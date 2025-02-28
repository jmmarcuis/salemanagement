<?php
namespace App\Livewire\Inventory;
use Livewire\Component;
use App\Models\Product;
use RealRashid\SweetAlert\Facades\Alert;
class DeleteProductModal extends Component
{
    public $isOpen = false;
    public $productId = null;
    public $productName = '';
   
    // Listen for events from the Index component
    protected $listeners = ['openDeleteProductModal' => 'show'];
   
    // Show the modal with product details
    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->productId = $id;
            $this->productName = $product->name;
            $this->isOpen = true;
        }
    }
   
    // Close the modal
    public function close()
    {
        $this->reset(['isOpen', 'productId', 'productName']);
    }
   
    // Delete the product
    public function destroy()
    {
        $product = Product::find($this->productId);
        if ($product) {
            $product->delete();
            $this->close();
            // Dispatch an event to refresh the product list
            $this->dispatch('productDeleted');
            // Flash a success message
            session()->flash('message', 'Product successfully deleted.');
        }
    }
   
    public function render()
    {
        return view('livewire.inventory.delete-product-modal');
    }
}