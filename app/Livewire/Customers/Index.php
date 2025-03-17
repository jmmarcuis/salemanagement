<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
        ])->layout('layouts.app');
    }

    public function addCustomer()
    {
        // Redirect to the customers/add route
        return redirect()->route('customers.add');
    }

    public function editCustomer($id)
    {
        // Redirect to the customers/edit route with the customer ID
        return redirect()->route('customers.edit', ['id' => $id]);
    }

    public function deleteCustomer($id)
    {
        // Get customer details for confirmation message
        $customer = Customer::find($id);

        if ($customer) {
            // Dispatch event to show delete confirmation
            $this->dispatch('swal:confirm', [
                'title' => 'Are you sure?',
                'text' => "Do you want to delete customer: {$customer->name}?",
                'icon' => 'warning',
                'confirmButtonText' => 'Yes, delete it!',
                'cancelButtonText' => 'Cancel',
                'method' => 'confirmDeleteCustomer',
                'params' => ['id' => $id]
            ]);
        }
    }

    public function confirmDeleteCustomer($id)
    {
        // Show loading first
        $this->dispatch('swal:loading');

        // Find the customer
        $customer = Customer::find($id);

        if ($customer) {
            $customerName = $customer->name;

            // Delete the customer
            $customer->delete();

            // Dispatch success message
            $this->dispatch('swal:message', [
                'title' => 'Deleted!',
                'text' => "Customer '{$customerName}' has been deleted.",
                'icon' => 'success',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'confirmButtonColor' => '#3085d6'
            ]);

            // Refresh the component
            $this->dispatch('customerDeleted');
        } else {
            // Dispatch error message
            $this->dispatch('swal:message', [
                'title' => 'Error!',
                'text' => "Customer could not be found.",
                'icon' => 'error',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'confirmButtonColor' => '#3085d6'
            ]);
        }
    }

    // Add listeners for events
    protected function getListeners()
    {
        return [
            'customerDeleted' => '$refresh',
            'confirmDeleteCustomer' => 'confirmDeleteCustomer',
        ];
    }
}