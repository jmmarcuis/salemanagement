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

class Edit extends Component
{
    // Main sale properties
    public $saleId;
    public $customerId;
    public $deadline;
    public $notes;
    public $vatType = VatType::STANDARD->value; 
    public $status = 'pending';
    public $showModal = false;
    public $showCustomerModalSearch = false;

    // Selected products
    public $selectedProducts = [];
    public $originalProducts = []; // To track changes for inventory management

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

    public function mount($id)
    {
        $this->saleId = $id;
        $sale = Sale::with(['items.product', 'customer', 'discounts'])->findOrFail($id);
        // Load sale details
        $this->customerId = $sale->customer_id;
        $this->searchCustomer = $sale->customer->name;
        $this->deadline = $sale->deadline->format('Y-m-d');
        $this->notes = $sale->notes;
        $this->vatType = $sale->vat_type->value; // Ensure this is the enum value
        $this->status = $sale->status->value;

        // Load sale items
        foreach ($sale->items as $item) {
            $this->selectedProducts[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_code' => $item->product->code,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'available_quantity' => $item->product->quantity + $item->quantity, // Add back the current quantity for validation
                'subtotal' => $item->subtotal,
            ];
        }

        // Store original products for inventory management
        $this->originalProducts = collect($this->selectedProducts)->map(function ($item) {
            return [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ];
        })->toArray();

        if ($sale->discounts->isNotEmpty()) {
            $discount = $sale->discounts->first();
            $this->discountType = $discount->discount_type->value;
            $this->discountValue = $discount->value;
            $this->discountDescription = $discount->description;
        }

        $this->calculateTotals();
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
        if ($this->discountType === DiscountType::FIXED->value) { // Ensure the enum value is used
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

    public function updateSale()
    {
        $this->validate();

        $this->dispatch('swal:loading');

        try {
            DB::beginTransaction();

            // Get the sale
            $sale = Sale::findOrFail($this->saleId);

            // Update the sale
            $sale->update([
                'customer_id' => $this->customerId,
                'total_amount' => $this->totalAmount,
                'discount_amount' => $this->discountAmount,
                'vat_amount' => $this->vatAmount,
                'grand_total' => $this->grandTotal,
                'status' => SaleStatus::from($this->status),
                'deadline' => $this->deadline,
                'notes' => $this->notes,
                'vat_type' => $this->vatType,
            ]);

            // Handle inventory adjustments
            $originalProductMap = collect($this->originalProducts)->keyBy('product_id');

            // First return all original quantities to inventory
            foreach ($originalProductMap as $productId => $info) {
                $product = Product::find($productId);
                $product->quantity += $info['quantity'];
                $product->save();
            }

            // Delete all existing sale items
            SaleItem::where('sale_id', $sale->id)->delete();

            // Create new sale items and adjust inventory
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
                } else {
                    $productModel->available = true;
                }
                $productModel->save();
            }

            // Update or create discount
            if ($this->discountAmount > 0) {
                Discount::updateOrCreate(
                    ['sale_id' => $sale->id],
                    [
                        'description' => $this->discountDescription,
                        'discount_type' => $this->discountType,
                        'value' => $this->discountValue,
                        'amount' => $this->discountAmount,
                    ]
                );
            } else {
                // Delete discount if it exists but is now zero
                Discount::where('sale_id', $sale->id)->delete();
            }

            DB::commit();

            session()->flash('message', 'Sale updated successfully!');
            // Dispatch success message with redirect
            $this->dispatch('swal:message:redirect', [
                'title' => 'Success!',
                'text' => 'Sale successfully updated.',
                'icon' => 'success',
                'route' => route('sales.index')
            ]);

            $this->dispatch('refresh-sales');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.edit', [
            'vatTypes' => VatType::cases(),
            'discountTypes' => DiscountType::cases(),  
            'saleStatuses' => SaleStatus::cases(),
        ])->layout('layouts.app');
    }
}
