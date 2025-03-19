<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleStatus;
use App\Models\VatType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Add extends Component
{
    // Main sale properties
    public $customerId;
    public $deadline;
    public $notes;
    public $vatType = 'standard';
    public $status = 'pending';
    public $showModal = false;
    public $showCustomerModalSearch = false;

    // Selected products
    public $selectedProducts = [];

    // Discount properties
    public $discountType = 'FIXED';
    public $discountValue = 0;
    public $discountDescription = 'Standard Discount';

    // Search and display properties
    public $searchProduct = '';
    public $searchCustomer = '';
    public $filteredCustomers = [];

    // Totals
    public $totalAmount = 0;
    public $vatAmount = 0;
    public $discountAmount = 0;
    public $grandTotal = 0;


    protected $listeners = [
        'productSelected' => 'handleProductSelected',
        'customerSelected' => 'handleCustomerSelected',
    ];

    protected $rules = [
        'customerId' => 'required',
        'deadline' => 'nullable|date',
        'selectedProducts' => 'required|array|min:1',
        'selectedProducts.*.quantity' => 'required|integer|min:1',
        'discountValue' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'customerId.required' => 'Please select a customer',
        'selectedProducts.required' => 'Please add at least one product',
        'selectedProducts.min' => 'Please add at least one product',
        'selectedProducts.*.quantity.required' => 'Quantity is required',
        'selectedProducts.*.quantity.min' => 'Quantity must be at least 1',
    ];

    public function mount()
    {
        $this->deadline = now()->addDays(7)->format('Y-m-d');
    }

    public function openProductModal()
    {
        $this->showModal = true;
        $this->dispatch('openProductSearchModal');
    }

    public function openCustomerModal()
    {
        $this->showCustomerModalSearch = true;
        $this->dispatch('openCustomerSearchModal');
    }

    public function handleCustomerSelected($customer)
    {
        $this->customerId = $customer['id'];
        $this->searchCustomer = $customer['name'];
        $this->showCustomerModalSearch = false;
    }

    public function handleProductSelected($product)
    {
        // Check if the product is already selected
        $existingKey = array_search($product['id'], array_column($this->selectedProducts, 'product_id'));

        if ($existingKey !== false) {
            // Increment quantity if product already exists
            $this->selectedProducts[$existingKey]['quantity'] += 1;
        } else {
            // Add the product if it doesn't exist yet
            $this->selectedProducts[] = [
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'product_code' => $product['code'],
                'unit_price' => $product['price'],
                'quantity' => 1,
                'available_quantity' => $product['quantity'],
                'subtotal' => $product['price'],
            ];
        }

        $this->calculateTotals();
    }

    public function searchCustomers()
    {
        if (strlen($this->searchCustomer) >= 2) {
            $this->filteredCustomers = Customer::where('name', 'like', '%' . $this->searchCustomer . '%')
                ->orWhere('email', 'like', '%' . $this->searchCustomer . '%')
                ->limit(10)
                ->get();
        } else {
            $this->filteredCustomers = [];
        }
    }



    public function selectCustomer($customerId)
    {
        $this->customerId = $customerId;
        $customer = Customer::find($customerId);
        $this->searchCustomer = $customer->name;
        $this->filteredCustomers = [];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
        $this->calculateTotals();
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity > 0 && $quantity <= $this->selectedProducts[$index]['available_quantity']) {
            $this->selectedProducts[$index]['quantity'] = $quantity;
            $this->selectedProducts[$index]['subtotal'] = $this->selectedProducts[$index]['unit_price'] * $quantity;
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->totalAmount = 0;
        foreach ($this->selectedProducts as $product) {
            $this->totalAmount += (float)$product['unit_price'] * (int)$product['quantity'];
        }

        // Calculate VAT
        if ($this->vatType === 'standard') {
            $this->vatAmount = (float)$this->totalAmount * 0.12;
        } else {
            $this->vatAmount = 0;
        }

        // Calculate discount
        if ($this->discountType === 'FIXED') {
            $this->discountAmount = min((float)$this->discountValue, (float)$this->totalAmount);
        } else {
            $percentage = min(100, (float)$this->discountValue);
            $this->discountAmount = ((float)$this->totalAmount * $percentage) / 100;
        }

        // Grand total
        $this->grandTotal = (float)$this->totalAmount + (float)$this->vatAmount - (float)$this->discountAmount;
        $this->grandTotal = max(0, $this->grandTotal);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if (in_array($propertyName, ['discountType', 'discountValue', 'vatType'])) {
            $this->calculateTotals();
        }

        if ($propertyName === 'searchCustomer') {
            $this->searchCustomers();
        }
    }

    public function saveSale()
    {
        $this->validate();

        $this->dispatch('swal:loading');


        try {
            DB::beginTransaction();

            // Create the sale
            $sale = Sale::create([
                'customer_id' => $this->customerId,
                'user_id' => Auth::id(),
                'total_amount' => $this->totalAmount,
                'discount_amount' => $this->discountAmount,
                'vat_amount' => $this->vatAmount,
                'grand_total' => $this->grandTotal,
                'status' => SaleStatus::from($this->status), // Cast to enum
                'deadline' => $this->deadline,
                'notes' => $this->notes,
                'vat_type' => $this->vatType,
            ]);

            // Create sale items
            foreach ($this->selectedProducts as $product) {
                $vatPerItem = 0;
                if ($this->vatType === 'STANDARD') {
                    $vatPerItem = $product['unit_price'] * $product['quantity'] * 0.12;
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'vat_amount' => $vatPerItem,
                    'subtotal' => $product['unit_price'] * $product['quantity'],
                ]);

                // Update product quantity
                $productModel = Product::find($product['product_id']);
                $productModel->quantity -= $product['quantity'];
                if ($productModel->quantity <= 0) {
                    $productModel->available = false;
                }
                $productModel->save();
            }

            // Add discount if applicable
            if ($this->discountAmount > 0) {
                Discount::create([
                    'sale_id' => $sale->id,
                    'description' => $this->discountDescription,
                    'discount_type' => $this->discountType,
                    'value' => $this->discountValue,
                    'amount' => $this->discountAmount,
                ]);
            }

            DB::commit();

            session()->flash('message', 'Sale created successfully!');
            // Dispatch success message with redirect
            $this->dispatch('swal:message:redirect', [
                'title' => 'Success!',
                'text' => 'Sales successfully added.',
                'icon' => 'success',
                'route' => route('sales.index')
            ]);

            $this->dispatch('refresh-sales');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.add', [
            'vatTypes' => VatType::cases(),
            'discountTypes' => DiscountType::cases(),
            'saleStatuses' => SaleStatus::cases(),
        ])->layout('layouts.app');
    }
}
