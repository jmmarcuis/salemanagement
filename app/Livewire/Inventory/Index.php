<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $isLoading = false;

    // ! ---------------------------------------- EXPORT AND IMPORT ------------------------------------ //

    public function futureFeature()
    {
        $this->dispatch('swal:future', [
            'title' => 'Coming Soon!',
            'text' => 'This feature will be available in a future update.',
        ]);
    } 

    public function exportCsv()
    {
        // Set loading state to true
        $this->isLoading = true;
    
        // Create a filename with timestamp
        $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';
    
        // Apply the same filters as in the view for consistency
        $export = new ProductsExport($this->search);
    
        // Reset loading state after export
        $this->isLoading = false;
    
        return Excel::download($export, $filename);
    }

    // ! ---------------------------------------------- CRUD ------------------------------------------ //

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
                'method' => 'confirmDeleteProduct',
                'params' => ['id' => $id]
            ]);
        }
    }

    // Method to handle deletion
    public function confirmDeleteProduct($id)
    {
        // Show loading first
        $this->dispatch('swal:loading');

        // Find the product
        $product = Product::find($id);

        if ($product) {
            $productName = $product->name;

            // Delete the product
            $product->delete();

            // Update the alert instead of closing and opening a new one
            $this->dispatch('swal:message', [
                'title' => 'Deleted!',
                'text' => "Product '{$productName}' has been deleted.",
                'icon' => 'success',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'confirmButtonColor' => '#3085d6'
            ]);

            // Refresh the component
            $this->dispatch('productDeleted');
        } else {
            // Update the alert with error message
            $this->dispatch('swal:message', [
                'title' => 'Error!',
                'text' => "Product could not be found.",
                'icon' => 'error',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'confirmButtonColor' => '#3085d6'
            ]);
        }
    }


    // ! ---------------------------------------------- CRUD ------------------------------------------ //


    // ! ---------------------------------------------- ^^^ FILTER ^^^ ------------------------------------------ //


    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    protected $queryString = ['search' => ['except' => '']];

    // Reset pagination when search is updated
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Header Sort By
    public function sortBy($field)
{
    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    $this->resetPage();  
}

    // Pagination Options
    public $perPage = 10;

    public function updatedPerPage()
    {
        $this->resetPage();

        // Use more explicit refresh technique
        $this->dispatch('refresh-products')->self();
    }
    // ! ---------------------------------------------- === FILTER === ------------------------------------------ //

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('code', 'like', '%' . $this->search . '%')
        ->orWhere('product_type', 'like', '%' . $this->search . '%')
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);


        return view('livewire.inventory.index', [
            'products' => $products
        ])->layout('layouts.app');
    }


    // Add listeners for events
    protected function getListeners()
    {
        return [
            'productDeleted' => '$refresh',
            'confirmDeleteProduct' => 'confirmDeleteProduct',
            'refresh-products' => '$refresh'
        ];
    }
}
