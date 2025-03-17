<div class="w-full h-full">
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-semibold">Sales</h3>
                        </div>
                        <div class="flex justify-between gap-2">
                            {{-- Add Sales --}}
                            <button
                                class="bg-gray-700 text-white flex align-middle hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="addSales">
                                Add Sales
                            </button>
                            {{-- Export to CSV --}}
                            <button
                                class="bg-gray-900 text-white flex align-middle hover:border-gray-900 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="exportCsv">
                                Export CSV
                            </button>
                            {{-- Import CSV Data to Table --}}
                            <button
                                class="bg-gray-900 text-white flex align-middle hover:border-gray-900 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="futureFeature">
                                Import CSV
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search customer name or sale ID"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <select wire:model.live="statusFilter"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="date" wire:model.live="dateFrom" placeholder="From Date"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <input type="date" wire:model.live="dateTo" placeholder="To Date"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="py-3 px-4 border-b text-left">ID</th>
                                    <th class="py-3 px-4 border-b text-left">Customer</th>
                                    <th class="py-3 px-4 border-b text-left">Date</th>
                                    <th class="py-3 px-4 border-b text-left">Deadline</th>
                                    <th class="py-3 px-4 border-b text-right">Amount</th>
                                    <th class="py-3 px-4 border-b text-center">Status</th>
                                    <th class="py-3 px-4 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $sale->id }}</td>
                                        <td class="py-3 px-4">{{ $sale->customer->name }}</td>
                                        <td class="py-3 px-4">{{ $sale->created_at->format('M d, Y') }}</td>
                                        <td class="py-3 px-4">
                                            {{ $sale->deadline ? $sale->deadline->format('M d, Y') : 'N/A' }}</td>
                                        <td class="py-3 px-4 text-right">₱ {{ number_format($sale->grand_total, 2) }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="px-2 py-1 rounded text-sm
                                                @if ($sale->status->value == 'complete') bg-green-100 text-green-800 
                                                @elseif($sale->status->value == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($sale->status->value == 'canceled') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($sale->status->value) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button wire:click="viewSale({{ $sale->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                View
                                            </button>
                                            <button wire:click="deleteSale({{ $sale->id }})"
                                                class="text-red-600 hover:text-red-900 mr-2">
                                                Delete
                                            </button>
                                            @if ($sale->status->value !== 'canceled' && $sale->status->value !== 'complete')
                                                <button wire:click="editSale({{ $sale->id }})"
                                                    class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500 dark:text-gray-400">No
                                            sales found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
