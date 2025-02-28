<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <h1 class="text-2xl font-bold">Inventory</h1>

                        <div class="flex justify-between gap-2">

                            {{-- ! Search Feature TODO ! --}}
                            <input type="text" wire:model.debounce.300ms="search"
                                class="block pt-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search for items">

                            {{-- Add Product --}}
                            <button
                                class="bg-gray-700 text-white flex align-middle hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="addProduct">
                                Add
                            </button>

                            {{-- Export to CSV --}}
                            <button
                                class="bg-gray-900 text-white flex align-middle hover:border-gray-900 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="addProduct">
                                Export CSV
                            </button>

                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <table class="w-full text-left table-auto min-w-max">
                        <thead>
                            <tr>
                                <th class="p-4 border-b border-slate-600 bg-slate-700">
                                    <p class="text-sm font-normal leading-none text-slate-300">
                                        ID
                                    </p>
                                </th>
                                <th class="p-4 border-b border-slate-600 bg-slate-700">
                                    <p class="text-sm font-normal leading-none text-slate-300">
                                        Code
                                    </p>
                                </th>
                                <th class="p-4 border-b border-slate-600 bg-slate-700">
                                    <p class="text-sm font-normal leading-none text-slate-300">
                                        Name
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
                                <th class="p-4 border-b border-slate-600 bg-slate-700">
                                    <p class="text-sm font-normal leading-none text-slate-300">
                                        Price
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
                                        <p class="text-sm text-slate-300">No products found</p>
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

    <!-- Delete Modal Component -->
    <livewire:inventory.delete-product-modal />

 <!-- SweetAlert2 Scripts -->
 <script>
    document.addEventListener('livewire:initialized', () => {
        // Success alerts
        @this.on('swal:success', (data) => {
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                icon: data[0].icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
        
        // Confirmation alerts
        @this.on('swal:confirm', (data) => {
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                icon: data[0].icon,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: data[0].confirmButtonText,
                cancelButtonText: data[0].cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('confirmDelete', data[0].productId);
                }
            });
        });
    });
</script>
</div>
