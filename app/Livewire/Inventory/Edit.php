<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;

class Edit extends Component
{
    // Product ID to be edited
    public $productId;

    // Form fields
    public $code;
    public $name;
    public $product_type;
    public $quantity = 0;
    public $price = 0;

    public $available = 1;

    // Validation rules
    protected $rules = [
        'code' => 'required|max:20',
        'name' => 'required|max:255',
        'product_type' => 'required|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'available' => 'required|integer|min:0|max:1',
    ];

    // Custom validation messages
    protected $messages = [
        'code.required' => 'Product code is required',
        'name.required' => 'Product name is required',
        'product_type.required' => 'Product type is required',
        'quantity.required' => 'Quantity is required',
        'quantity.integer' => 'Quantity must be a number',
        'price.required' => 'Price is Required',
    ];

    // Mount method to initialize component with product data
    public function mount($id)
    {
        $this->productId = $id;
        $product = Product::findOrFail($id);

        // Fill form fields with product data
        $this->code = $product->code;
        $this->name = $product->name;
        $this->product_type = $product->product_type;
        $this->quantity = $product->quantity;
        $this->price = number_format($product->price, 2, '.', '');
        $this->available = $product->available;
    }

    // Method to update the product
    public function updateProduct()
    {
        // Custom validation for unique code except for the current product
        $this->validate([
            'code' => 'required|max:20|unique:products,code,' . $this->productId,
            'name' => 'required|max:255',
            'product_type' => 'required|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'available' => 'required|integer|min:0|max:1',
        ]);

        // Get the product
        $product = Product::findOrFail($this->productId);

        // Update product data
        $product->update([
            'code' => $this->code,
            'name' => $this->name,
            'product_type' => $this->product_type,
            'quantity' => $this->quantity,
            'price' => floatval($this->price),
            'available' => $this->available,
        ]);

        // Dispatch browser event for SweetAlert2 with redirect
        $this->dispatch('swal:success:redirect', [
            'title' => 'Success!',
            'text' => 'Product successfully edited.',
            'icon' => 'success',
            'route' => route('inventory.index')
        ]);
    }

    // Method to go back to index
    public function backToIndex()
    {
        return redirect()->route('inventory.index');
    }

    public function render()
    {
        return view('livewire.inventory.edit')
            ->layout('layouts.app');
    }
}
