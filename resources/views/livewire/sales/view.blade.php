<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Sale Details #{{ $sale->id }}</h1>
                    <div>
                        <a href="{{ route('sales.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Back to Sales</a>
                        @if ($sale->status->value !== 'canceled' && $sale->status->value !== 'complete')
                            <a href="{{ route('sales.edit', ['id' => $sale->id]) }}" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Edit Sale</a>
                        @endif
                    </div>
                </div>

                <!-- Sale Info Card -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-lg font-medium mb-4">Sale Information</h2>
                            <div class="space-y-2">
                                <p><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded text-sm inline-block
                                        @if ($sale->status->value == 'complete') bg-green-100 text-green-800
                                        @elseif($sale->status->value == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($sale->status->value == 'canceled') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($sale->status->value) }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Date Created:</span> {{ $sale->created_at->format('F d, Y h:i A') }}</p>
                                <p><span class="font-medium">Deadline:</span> {{ $sale->deadline ? $sale->deadline->format('F d, Y') : 'N/A' }}</p>
                                <p><span class="font-medium">Authorized by:</span> {{ $sale->user->name }}</p>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-medium mb-4">Customer Information</h2>
                            <div class="space-y-2">
                                <p><span class="font-medium">Name:</span> {{ $sale->customer->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $sale->customer->email }}</p>
                                <p><span class="font-medium">Phone:</span> {{ $sale->customer->phone }}</p>
                                <p><span class="font-medium">Address:</span> {{ $sale->customer->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-6">
                    <h2 class="text-lg font-medium mb-4">Sale Items</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="py-3 px-4 text-left">Product</th>
                                    <th class="py-3 px-4 text-right">Unit Price</th>
                                    <th class="py-3 px-4 text-right">Quantity</th>
                                    <th class="py-3 px-4 text-right">VAT Amount</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->items as $item)
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="py-3 px-4">
                                        <div>
                                            <p class="font-medium">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Code: {{ $item->product->code }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right">₱ {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-4 text-right">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right">₱ {{ number_format($item->vat_amount, 2) }}</td>
                                    <td class="py-3 px-4 text-right">₱ {{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">No items found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Discounts -->
                @if(count($sale->discounts) > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-medium mb-4">Discounts Applied</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="py-3 px-4 text-left">Description</th>
                                    <th class="py-3 px-4 text-left">Type</th>
                                    <th class="py-3 px-4 text-right">Value</th>
                                    <th class="py-3 px-4 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->discounts as $discount)
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="py-3 px-4">{{ $discount->description }}</td>
                                    <td class="py-3 px-4">{{ $discount->discount_type->value }}</td>
                                    <td class="py-3 px-4 text-right">
                                        @if($discount->discount_type->value == 'PERCENTAGE')
                                            {{ $discount->value }}%
                                        @else
                                            ₱ {{ number_format($discount->value, 2) }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right">₱ {{ number_format($discount->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Summary -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6">
                    <h2 class="text-lg font-medium mb-4">Sale Summary</h2>
                    <div class="space-y-2 max-w-md ml-auto">
                        <div class="flex justify-between">
                            <span>Total Amount:</span>
                            <span class="font-medium">₱ {{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Discount Amount:</span>
                            <span class="font-medium">- ₱ {{ number_format($sale->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>VAT Amount:</span>
                            <span class="font-medium">₱ {{ number_format($sale->vat_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-300 dark:border-gray-600 pt-2 mt-2">
                            <span class="font-bold">Grand Total:</span>
                            <span class="font-bold">₱ {{ number_format($sale->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($sale->notes)
                <div class="mt-6">
                    <h2 class="text-lg font-medium mb-2">Notes</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p>{{ $sale->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>