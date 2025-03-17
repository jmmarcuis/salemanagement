<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;

class Add extends Component
{
    // Form fields
    public $code;
    public $name;
    public $product_type;
    public $quantity = 0;
    public $price = 0;
    public $available = 1;

    // Validation rules
    protected $rules = [
        'code' => 'required|unique:products,code|max:20',
        'name' => 'required|max:255',
        'product_type' => 'required|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'available' => 'required|integer|min:0|max:1',
    ];

    // Custom validation messages
    protected $messages = [
        'code.required' => 'Product code is required',
        'code.unique' => 'This product code already exists',
        'name.required' => 'Product name is required',
        'product_type.required' => 'Product type is required',
        'quantity.required' => 'Quantity is required',
        'quantity.integer' => 'Quantity must be a number',
        'price.required' => 'Price is Required',
     ];

    // Method to save the product
    public function saveProduct()
    {
        // Validate form data
        $this->validate();
    
        // Dispatch loading state
        $this->dispatch('swal:loading');
    
        // Create new product
        $product = Product::create([
            'code' => $this->code,
            'name' => $this->name,
            'product_type' => $this->product_type,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'available' => $this->available,
        ]);
    
        // Dispatch success message with redirect
        $this->dispatch('swal:message:redirect', [
            'title' => 'Success!',
            'text' => 'Product successfully added.',
            'icon' => 'success',
            'route' => route('inventory.index')
        ]);
    
        // Reset form fields
        $this->reset(['code', 'name', 'product_type', 'quantity', 'price']);
        $this->available = 1;
    }

    public function backToIndex()
    {
        // Redirect to the inventory index route
        return redirect()->route('inventory.index');
    }

    public function render()
    {
        return view('livewire.inventory.add')
            ->layout('layouts.app');
    }
}
