<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerSearchModal extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $showCustomerModalSearch = false;

    protected $listeners = ['openCustomerSearchModal' => 'openModal'];

    public function openModal()
    {
        $this->showCustomerModalSearch = true;
    }

    public function closeModal()
    {
        $this->showCustomerModalSearch = false;
    }

    public function selectCustomer(Customer $customer)
    {
        $this->dispatch('customerSelected', customer: $customer);
        $this->closeModal();
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.customer-search-modal', [
            'customers' => $customers,
            'showModal' => $this->showCustomerModalSearch,
        ])->layout('layouts.app');
    }
}   