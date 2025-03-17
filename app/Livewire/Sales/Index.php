<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\SaleStatus;
use App\Models\VatType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    // Method to handle the add sales button click
    public function addSales()
    {
        // Redirect to the sales.add route
        return redirect()->route('sales.add');
    }

    public function render()
    {
        $sales = Sale::query()
            ->with(['customer', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.sales.index', [
            'sales' => $sales,
            'statuses' => SaleStatus::cases(),
        ])->layout('layouts.app');
    }

    public function viewSale($id)
    {
        return redirect()->route('sales.view', ['id' => $id]);
    }

    public function editSale($id)
    {
        return redirect()->route('sales.edit', ['id' => $id]);
    }

    // Method to handle delete sale click
    public function deleteSale($id)
    {
        // Get sale details for confirmation message
        $sale = Sale::find($id);

        if ($sale) {
            // Dispatch event to show delete confirmation
            $this->dispatch('swal:confirm', [
                'title' => 'Are you sure?',
                'text' => "Do you want to delete Sale ID: {$sale->id}?",
                'icon' => 'warning',
                'confirmButtonText' => 'Yes, delete it!',
                'cancelButtonText' => 'Cancel',
                'method' => 'confirmDeleteSale',
                'params' => ['id' => $id]
            ]);
        }
    }

    public function confirmDeleteSale($id)
    {
        dd('confirmDeleteSale called');
        // Show loading first
        $this->dispatch('swal:loading');

        // Find the sale
        $sale = Sale::find($id);

        if ($sale) {
            try {

                // Delete the sale
                $sale->delete();


                // Update the alert instead of closing and opening a new one
                $this->dispatch('swal:message', [
                    'title' => 'Deleted!',
                    'text' => "Sale ID '{$sale->id}' has been deleted.",
                    'icon' => 'success',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'confirmButtonColor' => '#3085d6'
                ]);

                // Refresh the component
                $this->dispatch('saleDeleted');
            } catch (\Exception $e) {
                // Update the alert with error message
                $this->dispatch('swal:message', [
                    'title' => 'Error!',
                    'text' => "An error occurred while deleting the sale.",
                    'icon' => 'error',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'confirmButtonColor' => '#3085d6'
                ]);
            }
        } else {
            // Update the alert with error message
            $this->dispatch('swal:message', [
                'title' => 'Error!',
                'text' => "Sale could not be found.",
                'icon' => 'error',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'confirmButtonColor' => '#3085d6'
            ]);
        }
    }
  

    public function exportCsv()
    {
        // This will be implemented later
        $this->dispatch('notify', ['message' => 'Export feature will be available soon!']);
    }

    public function futureFeature()
    {
        $this->dispatch('notify', ['message' => 'This feature will be available soon!']);
    }

    // Add listeners for events
    protected function getListeners()
    {
        return [
            'refresh-sales' => '$refresh',
            'saleDeleted' => '$refresh',

        ];
    }
}
