<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <h1 class="text-2xl font-bold">Edit Product</h1>
                        <button
                            class="bg-gray-700 text-white flex align-middle hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                            wire:click="backToIndex">
                            Back
                        </button>
                    </div>

                    <!-- Flash Message -->
                    @if (session()->has('message'))
                        <div class="bg-green-500 text-white p-4 mb-4 rounded">
                            {{ session('message') }}
                        </div>
                    @endif

                    <!-- Edit Product Form -->
                    <form wire:submit.prevent="updateProduct" class="space-y-6">
                        <!-- Product Code -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-300">Product Code</label>
                            <input type="text" id="code" wire:model="code"
                                class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                            @error('code')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Product Name</label>
                            <input type="text" id="name" wire:model="name"
                                class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Type -->
                        <div>
                            <label for="product_type" class="block text-sm font-medium text-gray-300">Product
                                Type</label>
                            <input type="text" id="product_type" wire:model="product_type"
                                class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                            @error('product_type')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-300">Quantity</label>
                            <input type="number" id="quantity" wire:model="quantity" min="0"
                                class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                            @error('quantity')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                            <input type="number" id="price" wire:model="price" min="0"
                                class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                            @error('price')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Availability -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Availability</label>
                            <div class="mt-2 space-x-4 flex items-center">
                                <div class="flex items-center">
                                    <input id="available-yes" type="radio" wire:model="available" value="1"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="available-yes" class="ml-2 block text-sm text-gray-300">
                                        Available
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="available-no" type="radio" wire:model="available" value="0"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="available-no" class="ml-2 block text-sm text-gray-300">
                                        Not Available
                                    </label>
                                </div>
                            </div>
                            @error('available')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-105">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>
