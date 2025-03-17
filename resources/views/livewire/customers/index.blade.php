<div class="w-full h-full">
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-semibold">Customers</h3>
                        </div>
                        <div class="flex justify-between gap-2">
                            <button
                                class="bg-gray-700 text-white flex align-middle hover:border-gray-700 py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105"
                                wire:click="addCustomer">
                                Add Customer
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search customer name, email, or phone"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                    </div>

                    <!-- Customers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="py-3 px-4 border-b text-left">ID</th>
                                    <th class="py-3 px-4 border-b text-left">Customer Name</th>
                                    <th class="py-3 px-4 border-b text-left">Email</th>
                                    <th class="py-3 px-4 border-b text-left">Phone Number</th>
                                    <th class="py-3 px-4 border-b text-left">Address</th>
                                    <th class="py-3 px-4 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $customer->id }}</td>
                                        <td class="py-3 px-4">{{ $customer->name }}</td>
                                        <td class="py-3 px-4">{{ $customer->email }}</td>
                                        <td class="py-3 px-4">{{ $customer->phone }}</td>
                                        <td class="py-3 px-4">{{ $customer->address }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <button wire:click="editCustomer({{ $customer->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                Edit
                                            </button>
                                            <button wire:click="deleteCustomer({{ $customer->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-400">No customers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>