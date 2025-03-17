<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleStatus;
use App\Models\VatType;
use App\Models\DiscountType;
use App\Models\SaleItem;
use App\Models\Discount;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $sale;
    public $saleId;
    public $customer_id;
    public $status;
    public $deadline;
    public $notes;
    public $vat_type;
    public $products = [];
    public $selectedProducts = [];
    public $discounts = [];
    public $total_amount = 0;
    public $discount_amount = 0;
    public $vat_amount = 0;
    public $grand_total = 0;
    public $customers = [];
    public $statuses = [];
    public $vatTypes = [];
    public $discountTypes = [];
    public $availableProducts = [];

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'status' => 'required',
        'deadline' => 'nullable|date',
        'notes' => 'nullable|string',
        'vat_type' => 'required',
        'selectedProducts' => 'required|array|min:1',
        'selectedProducts.*.product_id' => 'required|exists:products,id',
        'selectedProducts.*.quantity' => 'required|numeric|min:1',
        'selectedProducts.*.unit_price' => 'required|numeric|min:0',
        'discounts.*.description' => 'nullable|string',
        'discounts.*.discount_type' => 'nullable|string',
        'discounts.*.value' => 'nullable|numeric|min:0',
    ];

    public function mount($id)
    {
        $this->saleId = $id;
        $this->customers = Customer::all();
        $this->statuses = SaleStatus::cases();
        $this->vatTypes = VatType::cases();
        $this->discountTypes = DiscountType::cases();
        $this->availableProducts = Product::where('available', true)->get();

        $this->loadSale();
        $this->initializeForm();
    }

    public function loadSale()
    {
        $this->sale = Sale::with(['customer', 'items.product', 'discounts'])
            ->findOrFail($this->saleId);
    }

    public function initializeForm()
    {
        $this->customer_id = $this->sale->customer_id;
        $this->status = $this->sale->status->value;
        $this->deadline = $this->sale->deadline ? $this->sale->deadline->format('Y-m-d') : null;
        $this->notes = $this->sale->notes;
        $this->vat_type = $this->sale->vat_type->value;
        $this->total_amount = $this->sale->total_amount;
        $this->discount_amount = $this->sale->discount_amount;
        $this->vat_amount = $this->sale->vat_amount;
        $this->grand_total = $this->sale->grand_total;

        // Initialize selected products from sale items
        $this->selectedProducts = $this->sale->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'code' => $item->product->code,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'vat_amount' => $item->vat_amount,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();

        // Initialize discounts
        $this->discounts = $this->sale->discounts->map(function ($discount) {
            return [
                'id' => $discount->id,
                'description' => $discount->description,
                'discount_type' => $discount->discount_type->value,
                'value' => $discount->value,
                'amount' => $discount->amount,
            ];
        })->toArray();

        if (empty($this->discounts)) {
            $this->addDiscount();
        }
    }

    public function addProduct()
    {
        $this->selectedProducts[] = [
            'product_id' => '',
            'name' => '',
            'code' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'vat_amount' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
        $this->calculateTotals();
    }

    public function updatedSelectedProducts($value, $index)
    {
        // Extract the product_id and position from the nested key
        if (strpos($index, '.product_id') !== false) {
            $position = explode('.', $index)[0];
            $productId = $value;

            $product = $this->availableProducts->firstWhere('id', $productId);
            if ($product) {
                $this->selectedProducts[$position]['name'] = $product->name;
                $this->selectedProducts[$position]['code'] = $product->code;
                $this->selectedProducts[$position]['unit_price'] = $product->price;
                $this->calculateProductSubtotal($position);
            }
        } elseif (strpos($index, '.quantity') !== false || strpos($index, '.unit_price') !== false) {
            $position = explode('.', $index)[0];
            $this->calculateProductSubtotal($position);
        }
    }

    public function calculateProductSubtotal($index)
    {
        $product = $this->selectedProducts[$index];
        $quantity = $product['quantity'];
        $unitPrice = $product['unit_price'];

        // Calculate VAT
        $vatAmount = 0;
        if ($this->vat_type === VatType::STANDARD->value) {
            $vatAmount = $quantity * $unitPrice * 0.12; // 12% VAT
        }

        $subtotal = $quantity * $unitPrice + $vatAmount;

        $this->selectedProducts[$index]['vat_amount'] = $vatAmount;
        $this->selectedProducts[$index]['subtotal'] = $subtotal;

        $this->calculateTotals();
    }

    public function addDiscount()
    {
        $this->discounts[] = [
            'description' => '',
            'discount_type' => DiscountType::FIXED->value,
            'value' => 0,
            'amount' => 0,
        ];
    }

    public function removeDiscount($index)
    {
        unset($this->discounts[$index]);
        $this->discounts = array_values($this->discounts);
        $this->calculateTotals();
    }

    public function updatedDiscounts($value, $index)
    {
        if (strpos($index, '.discount_type') !== false || strpos($index, '.value') !== false) {
            $position = explode('.', $index)[0];
            $this->calculateDiscountAmount($position);
        }
    }

    public function calculateDiscountAmount($index)
    {
        $discount = $this->discounts[$index];
        $discountType = $discount['discount_type'];
        $value = $discount['value'];

        if ($discountType === DiscountType::PERCENTAGE->value) {
            $this->discounts[$index]['amount'] = $this->total_amount * ($value / 100);
        } else {
            $this->discounts[$index]['amount'] = $value;
        }

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        // Calculate total amount from products
        $this->total_amount = array_reduce($this->selectedProducts, function ($carry, $product) {
            return $carry + ($product['quantity'] * $product['unit_price']);
        }, 0);

        // Calculate VAT amount
        $this->vat_amount = 0;
        if ($this->vat_type === VatType::STANDARD->value) {
            $this->vat_amount = array_reduce($this->selectedProducts, function ($carry, $product) {
                return $carry + $product['vat_amount'];
            }, 0);
        }

        // Calculate discount amount
        $this->discount_amount = array_reduce($this->discounts, function ($carry, $discount) {
            return $carry + ($discount['amount'] ?? 0);
        }, 0);

        // Calculate grand total
        $this->grand_total = $this->total_amount + $this->vat_amount - $this->discount_amount;
    }

    public function updatedVatType()
    {
        // Recalculate VAT for all products
        foreach ($this->selectedProducts as $index => $product) {
            $this->calculateProductSubtotal($index);
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update sale record
            $this->sale->update([
                'customer_id' => $this->customer_id,
                'status' => $this->status,
                'deadline' => $this->deadline,
                'notes' => $this->notes,
                'total_amount' => $this->total_amount,
                'discount_amount' => $this->discount_amount,
                'vat_amount' => $this->vat_amount,
                'grand_total' => $this->grand_total,
                'vat_type' => $this->vat_type,
            ]);

            // Handle sale items
            $existingItemIds = [];

            foreach ($this->selectedProducts as $product) {
                if (isset($product['id'])) {
                    // Update existing item
                    SaleItem::where('id', $product['id'])->update([
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'vat_amount' => $product['vat_amount'],
                        'subtotal' => $product['subtotal'],
                    ]);
                    $existingItemIds[] = $product['id'];
                } else {
                    // Create new item
                    $saleItem = new SaleItem([
                        'sale_id' => $this->sale->id,
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'vat_amount' => $product['vat_amount'],
                        'subtotal' => $product['subtotal'],
                    ]);
                    $this->sale->items()->save($saleItem);
                    $existingItemIds[] = $saleItem->id;
                }
            }

            // Delete items not in the current list
            $this->sale->items()->whereNotIn('id', $existingItemIds)->delete();

            // Handle discounts
            $existingDiscountIds = [];

            foreach ($this->discounts as $discount) {
                if (!empty($discount['description']) && !empty($discount['value'])) {
                    if (isset($discount['id'])) {
                        // Update existing discount
                        Discount::where('id', $discount['id'])->update([
                            'description' => $discount['description'],
                            'discount_type' => $discount['discount_type'],
                            'value' => $discount['value'],
                            'amount' => $discount['amount'],
                        ]);
                        $existingDiscountIds[] = $discount['id'];
                    } else {
                        // Create new discount
                        $discountModel = new Discount([
                            'sale_id' => $this->sale->id,
                            'description' => $discount['description'],
                            'discount_type' => $discount['discount_type'],
                            'value' => $discount['value'],
                            'amount' => $discount['amount'],
                        ]);
                        $this->sale->discounts()->save($discountModel);
                        $existingDiscountIds[] = $discountModel->id;
                    }
                }
            }

            // Delete discounts not in the current list
            $this->sale->discounts()->whereNotIn('id', $existingDiscountIds)->delete();

            DB::commit();

            session()->flash('message', 'Sale updated successfully.');
            return redirect()->route('sales.view', ['id' => $this->sale->id]);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.edit')->layout('layouts.app');;
    }
}
