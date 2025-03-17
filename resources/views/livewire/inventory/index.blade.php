    <div class="w-full h-full">
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between mb-4">
                            <div class="flex justify-between gap-2">
                                {{-- Add Product --}}
                                <button
                                    class="bg-gray-700 text-white flex align-middle hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                    wire:click="addProduct">
                                    Add Product
                                </button>

                                {{--  Search Feature --}}
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    class="block pt-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search for items">

                            </div>

                            <div class="flex justify-between gap-2">
                                {{-- Export to CSV --}}
                                <button
                                    class="bg-gray-900 text-white flex align-middle hover:border-gray-900 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:click="exportCsv" wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed">
                                    <span wire:loading.remove>Export CSV</span>
                                    <span wire:loading>
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                                {{-- Import CSV Data to Table --}}
                                <button
                                    class="bg-gray-900 text-white flex align-middle hover:border-gray-900 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                    wire:click="futureFeature">
                                    Import CSV
                                </button>
                            </div>
                        </div>

                        {{-- Pagination Options --}}
                        <div class="mb-4">
                            <label for="perPage" class="text-sm text-gray-500 dark:text-gray-400">Rows per page:</label>
                            <select wire:model.live="perPage" wire:change="$refresh" id="perPage"
                                class="ml-2 text-sm border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        <!-- Inventory Table -->
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700 cursor-pointer"
                                        wire:click="sortBy('id')">
                                        <p class="text-sm font-normal leading-none text-slate-300 flex items-center">
                                            ID
                                            @if ($sortField === 'id')
                                                <span>
                                                    @if ($sortDirection === 'asc')
                                                        <x-heroicon-o-chevron-up class="w-5 h-5" />
                                                    @else
                                                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    </th>

                                    <th class="p-4 border-b border-slate-600 bg-slate-700">
                                        <p class="text-sm font-normal leading-none text-slate-300">
                                            Code
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700 cursor-pointer"
                                        wire:click="sortBy('name')">
                                        <p class="text-sm font-normal leading-none text-slate-300 flex items-center">
                                            Name
                                            @if ($sortField === 'name')
                                                <span>
                                                    @if ($sortDirection === 'asc')
                                                        <x-heroicon-o-chevron-up class="w-5 h-5" />
                                                    @else
                                                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700">
                                        <p class="text-sm font-normal leading-none text-slate-300">
                                            Product Type
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700">
                                        <p class="text-sm font-normal leading-none text-slate-300">
                                            Quantity
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700 cursor-pointer"
                                        wire:click="sortBy('price')">
                                        <p class="text-sm font-normal leading-none text-slate-300 flex items-center">
                                            Price
                                            @if ($sortField === 'price')
                                                <span>
                                                    @if ($sortDirection === 'asc')
                                                        <x-heroicon-o-chevron-up class="w-5 h-5" />
                                                    @else
                                                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700">
                                        <p class="text-sm font-normal leading-none text-slate-300">
                                            Available?
                                        </p>
                                    </th>
                                    <th class="p-4 border-b border-slate-600 bg-slate-700">
                                        <p class="text-sm font-normal leading-none text-slate-300">
                                            Action
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-100 font-semibold">
                                                {{ $product->id }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                {{ $product->code }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                {{ $product->name }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                {{ $product->product_type }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                {{ $product->quantity }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                {{ $product->price }}
                                            </p>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <div class="w-max">
                                                <div
                                                    class="relative grid items-center px-2 py-1 font-sans text-xs font-bold {{ $product->available ? 'text-green-900 bg-green-300' : 'text-red-900 bg-red-300' }} uppercase rounded-md select-none whitespace-nowrap">
                                                    <span
                                                        class="">{{ $product->available ? 'Available' : 'Not Available' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 border-b border-slate-700">
                                            <p class="text-sm text-slate-300">
                                                <button wire:click="deleteProduct({{ $product->id }})"
                                                    class="text-red-600 hover:underline transition duration-300 ease-in-out transform hover:scale-105">
                                                    <x-heroicon-o-trash class="w-5 h-5" />
                                                </button>
                                                <button wire:click="editProduct({{ $product->id }})"
                                                    class="text-white hover:underline transition duration-300 ease-in-out transform hover:scale-105">
                                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                                </button>
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-4 border-b border-slate-700 text-center">
                                            <p class="text-sm text-slate-300">
                                                No products found
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
