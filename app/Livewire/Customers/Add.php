<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class Add extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        // Dispatch loading state
        $this->dispatch('swal:loading');

        Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

          // Dispatch success message with redirect
          $this->dispatch('swal:message:redirect', [
            'title' => 'Success!',
            'text' => 'Customer successfully added.',
            'icon' => 'success',
            'route' => route('customers.index')
        ]);
    

        $this->reset();
        session()->flash('message', 'Customer added successfully.');
    }

    public function backToIndex()
    {
        // Redirect to the customer index route
        return redirect()->route('customers.index');
    }

    public function render()
    {
        
        return view('livewire.customers.add', [
            
        ])->layout('layouts.app');
    }
}