<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Edit Sale #{{ $sale->id }}</h1>
                    <div>
                        <a href="{{ route('sales.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <!-- Sale Information Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 mb-6">
                        <h2 class="text-lg font-medium mb-4">Sale Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                                <select id="customer_id" wire:model="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="status" wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption->value }}">{{ ucfirst($statusOption->value) }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                                <input type="date" id="deadline" wire:model="deadline" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                @error('deadline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="vat_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">VAT Type</label>
                                <select id="vat_type" wire:model="vat_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                    @foreach ($vatTypes as $vatTypeOption)
                                        <option value="{{ $vatTypeOption->value }}">{{ str_replace('_', ' ', ucfirst($vatTypeOption->value)) }}</option>
                                    @endforeach
                                </select>
                                @error('vat_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                                <textarea id="notes" wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-medium">Products</h2>
                            <button type="button" wire:click="addProduct" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Add Product
                            </button>
                        </div>
                        
                        @error('selectedProducts') <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> @enderror
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="py-3 px-4 text-left">Product</th>
                                        <th class="py-3 px-4 text-right">Unit Price</th>
                                        <th class="py-3 px-4 text-right">Quantity</th>
                                        <th class="py-3 px-4 text-right">VAT Amount</th>
                                        <th class="py-3 px-4 text-right">Subtotal</th>
                                        <th class="py-3 px-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($selectedProducts as $index => $product)
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">
                                            <select wire:model="selectedProducts.{{ $index }}.product_id" wire:change="updatedSelectedProducts($event.target.value, '{{ $index }}.product_id')" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                                <option value="">Select Product</option>
                                                @foreach ($availableProducts as $availableProduct)
                                                    <option value="{{ $availableProduct->id }}">{{ $availableProduct->name }} ({{ $availableProduct->code }})</option>
                                                @endforeach
                                            </select>
                                            @error("selectedProducts.{$index}.product_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="number" step="0.01" wire:model="selectedProducts.{{ $index }}.unit_price" wire:change="updatedSelectedProducts($event.target.value, '{{ $index }}.unit_price')" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                            @error("selectedProducts.{$index}.unit_price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="number" min="1" wire:model="selectedProducts.{{ $index }}.quantity" wire:change="updatedSelectedProducts($event.target.value, '{{ $index }}.quantity')" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                            @error("selectedProducts.{$index}.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="py-3 px-4 text-right">₱ {{ number_format($product['vat_amount'] ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 text-right">₱ {{ number_format($product['subtotal'] ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <button type="button" wire:click="removeProduct({{ $index }})" class="text-red-600 hover:text-red-900">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-400">No products added</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Discounts Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-medium">Discounts</h2>
                            <button type="button" wire:click="addDiscount" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Add Discount
                            </button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="py-3 px-4 text-left">Description</th>
                                        <th class="py-3 px-4 text-left">Type</th>
                                        <th class="py-3 px-4 text-right">Value</th>
                                        <th class="py-3 px-4 text-right">Amount</th>
                                        <th class="py-3 px-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($discounts as $index => $discount)
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">
                                            <input type="text" wire:model="discounts.{{ $index }}.description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                        </td>
                                        <td class="py-3 px-4">
                                            <select wire:model="discounts.{{ $index }}.discount_type" wire:change="updatedDiscounts($event.target.value, '{{ $index }}.discount_type')" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                                @foreach ($discountTypes as $discountType)
                                                    <option value="{{ $discountType->value }}">{{ $discountType->value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="number" step="0.01" min="0" wire:model="discounts.{{ $index }}.value" wire:change="updatedDiscounts($event.target.value, '{{ $index }}.value')" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                        </td>
                                        <td class="py-3 px-4 text-right">₱ {{ number_format($discount['amount'] ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <button type="button" wire:click="removeDiscount({{ $index }})" class="text-red-600 hover:text-red-900">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 mb-6">
                        <h2 class="text-lg font-medium mb-4">Sale Summary</h2>
                        <div class="space-y-2 max-w-md ml-auto">
                            <div class="flex justify-between">
                                <span>Total Amount:</span>
                                <span class="font-medium">₱ {{ number_format($total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Discount Amount:</span>
                                <span class="font-medium">- ₱ {{ number_format($discount_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>VAT Amount:</span>
                                <span class="font-medium">₱ {{ number_format($vat_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-300 dark:border-gray-600 pt-2 mt-2">
                                <span class="font-bold">Grand Total:</span>
                                <span class="font-bold">₱ {{ number_format($grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Update Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>