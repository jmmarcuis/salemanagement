<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class Edit extends Component
{
    public $id; // Change from customerId to id
    public $name;
    public $email;
    public $phone;
    public $address;

    public function mount($id) // Change parameter to match route
    {
        $this->id = $id;
        $customer = Customer::findOrFail($id);

        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $this->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $this->dispatch('swal:loading');


        $customer = Customer::findOrFail($this->id);
        $customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        // Dispatch success message with redirect
        $this->dispatch('swal:message:redirect', [
            'title' => 'Success!',
            'text' => 'Customer successfully edited.',
            'icon' => 'success',
            'route' => route('customers.index')
        ]);
    }

    
    public function backToIndex()
    {
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.edit')->layout('layouts.app');
    }
}
