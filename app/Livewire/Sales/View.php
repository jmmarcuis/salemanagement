<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Barryvdh\Snappy\Facades\SnappyPdf;

class View extends Component
{
    public $sale;
    public $saleId;

    public function mount($id)
    {
        $this->saleId = $id;
        $this->loadSale();
    }

    public function loadSale()
    {
        $this->sale = Sale::with(['customer', 'user', 'items.product', 'discounts'])
            ->findOrFail($this->saleId);
    }

    public function downloadPdf()
    {
        $sale = $this->sale;
        $pdf = SnappyPdf::loadView('pdfs.sale', compact('sale'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "sale_{$sale->id}.pdf");
    }

    public function render()
    {
        return view('livewire.sales.view')->layout('layouts.app');
    }
}