<div class="w-full h-full">
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-6">Create New Sale</h3>

                    <form wire:submit.prevent="saveSale">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Customer Selection -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Customer</label>
                                <div class="relative">
                                    <button type="button" wire:click="openCustomerModal"
                                        class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-left flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <!-- Conditional display based on whether a customer is selected -->
                                        @if ($customerId)
                                            <span class="text-gray-900 dark:text-gray-100">{{ $searchCustomer }}</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Click to search and select
                                                customers...</span>
                                        @endif
                                    </button>
                                </div>
                                @error('customerId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Deadline -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Order Deadline</label>
                                <input type="date" wire:model="deadline"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('deadline')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select wire:model="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @foreach ($saleStatuses as $saleStatus)
                                        <option value="{{ $saleStatus->value }}">{{ ucfirst($saleStatus->value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- VAT Type -->
                            <div>
                                <label class="block text-sm font-medium mb-1">VAT Type</label>
                                <select wire:model.live="vatType"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @foreach ($vatTypes as $vType)
                                        <option value="{{ $vType->name }}">
                                            {{ ucfirst(str_replace('_', ' ', strtolower($vType->value))) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Notes</label>
                                <input type="text" wire:model="notes" placeholder="Additional notes"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Product Search Button -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">Add Products</label>
                            <button type="button" wire:click="openProductModal"
                                class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-left flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="text-gray-500 dark:text-gray-400">Click to search and select
                                    products...</span>
                            </button>
                            @error('selectedProducts')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Selected Products -->
                        <div class="mb-6">
                            <h4 class="text-lg font-medium mb-2">Selected Products</h4>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                                    <thead>
                                        <tr class="bg-gray-100 dark:bg-gray-700">
                                            <th class="py-3 px-4 border-b text-left">Code</th>
                                            <th class="py-3 px-4 border-b text-left">Product</th>
                                            <th class="py-3 px-4 border-b text-right">Unit Price</th>
                                            <th class="py-3 px-4 border-b text-center">Quantity</th>
                                            <th class="py-3 px-4 border-b text-right">Subtotal</th>
                                            <th class="py-3 px-4 border-b text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($selectedProducts as $index => $product)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                                                <td class="py-3 px-4">{{ $product['product_code'] }}</td>
                                                <td class="py-3 px-4">{{ $product['product_name'] }}</td>
                                                <td class="py-3 px-4 text-right">₱
                                                    {{ number_format($product['unit_price'], 2) }}</td>
                                                <td class="py-3 px-4 text-center">
                                                    <input type="number" min="1"
                                                        max="{{ $product['available_quantity'] }}"
                                                        value="{{ $product['quantity'] }}"
                                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                        class="w-20 text-center rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                    @error("selectedProducts.{$index}.quantity")
                                                        <span class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td class="py-3 px-4 text-right">₱
                                                    {{ number_format($product['subtotal'], 2) }}</td>
                                                <td class="py-3 px-4 text-center">
                                                    <button type="button"
                                                        wire:click="removeProduct({{ $index }})"
                                                        class="text-red-600 hover:text-red-900">
                                                        Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"
                                                    class="py-6 text-center text-gray-500 dark:text-gray-400">No
                                                    products selected</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Discount Section -->
                        <div class="mb-6">
                            <h4 class="text-lg font-medium mb-2">Discount</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Discount Type</label>
                                    <select wire:model.live="discountType"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        @foreach ($discountTypes as $dType)
                                            <option value="{{ $dType->value }}">
                                                {{ ucfirst(strtolower($dType->value)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Discount Value</label>
                                    <input type="number" wire:model.live="discountValue" min="0"
                                        @if ($discountType === 'percentage') max="100" @endif
                                        placeholder="{{ $discountType === 'percentage' ? 'Percentage' : 'Amount' }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @error('discountValue')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Discount Description</label>
                                    <input type="text" wire:model="discountDescription" placeholder="Description"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Totals Section -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-medium mb-4">Order Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <!-- Breakdown of costs -->
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span>₱ {{ number_format($totalAmount, 2) }}</span>
                                        </div>
                                        @if ($discountAmount > 0)
                                            <div class="flex justify-between text-red-600 dark:text-red-400">
                                                <span>Discount:</span>
                                                <span>- ₱ {{ number_format($discountAmount, 2) }}</span>
                                            </div>
                                        @endif
                                        @if ($vatAmount > 0)
                                            <div class="flex justify-between">
                                                <span>VAT (12%):</span>
                                                <span>₱ {{ number_format($vatAmount, 2) }}</span>
                                            </div>
                                        @endif
                                        <div
                                            class="flex justify-between font-bold text-lg pt-2 border-t border-gray-300 dark:border-gray-600">
                                            <span>Total:</span>
                                            <span>₱ {{ number_format($grandTotal, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <!-- Additional information -->
                                    <div
                                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-300 dark:border-gray-700">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <span class="font-medium">VAT Type:</span>
                                            {{ ucfirst(str_replace('_', ' ', strtolower($vatType))) }}
                                        </p>
                                        @if ($vatType === 'STANDARD')
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Standard VAT rate of 12% will be applied to this order.
                                            </p>
                                        @elseif($vatType === 'ZERO_RATED')
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Zero-rated VAT applies to specific goods and services as per Philippines
                                                tax laws.
                                            </p>
                                        @elseif($vatType === 'VAT_EXEMPT')
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                This transaction is exempt from VAT as per Philippines tax regulations.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('sales.index') }}"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md text-gray-800 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 rounded-md text-white hover:bg-blue-700 transition"
                                @if (empty($selectedProducts)) disabled @endif>
                                Save Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @livewire('product-search-modal')
    @livewire('customer-search-modal')


</div>
