<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    // Manage sidebar state
    public $sidebarOpen = true;
    public $salesDropdownOpen = false;
 
    public function mount(): void
    {
        // Initialize from Alpine.js (handled via x-init now)
    }

    public function toggleSidebar(): void
    {
        $this->sidebarOpen = !$this->sidebarOpen;
    }
    
    public function toggleSalesDropdown(): void
    {
        $this->salesDropdownOpen = !$this->salesDropdownOpen;
    }
}; ?>


<div 
    x-data="{ 
        sidebarOpen: $wire.entangle('sidebarOpen').defer,
        salesDropdownOpen: $wire.entangle('salesDropdownOpen').defer
    }" 
    x-init="
        // Load sidebar state from localStorage on component initialization
        sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
        $wire.sidebarOpen = sidebarOpen;
        
        // Watch for changes to sidebarOpen and save to localStorage
        $watch('sidebarOpen', value => {
            localStorage.setItem('sidebarOpen', value);
        });
    "
    class="flex h-screen bg-gray-100 dark:bg-gray-900">
    
    <!-- Sidebar -->
    <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full sm:translate-x-0 sm:w-20': !sidebarOpen}" 
        class="fixed sm:relative z-30 transition-all duration-300 transform bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 w-64 h-screen overflow-y-auto">
        
        <!-- Logo and Toggle Button -->
        <div class="flex items-center justify-between p-4 h-16">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                <span x-cloak x-show="sidebarOpen" class="ml-3 text-xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>
            <!-- Toggle Button (Both Mobile and Desktop) -->
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="px-2 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" wire:navigate 
                class="flex items-center px-4 py-2 {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-md text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="ml-3">{{ __('Dashboard') }}</span>
            </a>
            
            {{-- Inventory --}}
            <a href="{{ route('inventory.index') }}" wire:navigate
                class="flex items-center px-4 py-2 {{ request()->routeIs('inventory*') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-md text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="ml-3">{{ __('Inventory') }}</span>
            </a>

            {{-- Customer --}}
            <a href="{{ route('customers.index') }}" wire:navigate
                class="flex items-center px-4 py-2 {{ request()->routeIs('customers*') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-md text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="ml-3">{{ __('Customers') }}</span>
            </a>
            
            <!-- Sales and Collections Dropdown -->
            <div class="relative">
                <button @click="salesDropdownOpen = !salesDropdownOpen" 
                    class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('sales*') || request()->routeIs('collections*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="ml-3">{{ __('Sales & Collections') }}</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" :class="salesDropdownOpen ? 'transform rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-cloak x-show="salesDropdownOpen" class="pl-9 mt-1 space-y-1">
                    <a href="{{ route('sales.index') }}" wire:navigate 
                        class="block px-4 py-2 rounded-md text-sm {{ request()->routeIs('sales.index') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} text-gray-700 dark:text-gray-300">
                        {{ __('Sales Orders') }}
                    </a>
                    {{-- <a href="{{ route('collections.index') }}" wire:navigate 
                        class="block px-4 py-2 rounded-md text-sm {{ request()->routeIs('collections.index') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} text-gray-700 dark:text-gray-300">
                        {{ __('Collections') }}
                    </a>
                    <a href="{{ route('customers.index') }}" wire:navigate 
                        class="block px-4 py-2 rounded-md text-sm {{ request()->routeIs('customers.index') ? 'bg-gray-200 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} text-gray-700 dark:text-gray-300">
                        {{ __('Customers') }}
                    </a> --}}
                </div>
            </div>
        </div>
 
        <!-- User Section at Bottom -->
        <div class="absolute bottom-0 w-full border-t border-gray-100 dark:border-gray-700 p-4">
            <div x-data="{ userMenuOpen: false }" class="relative">
                <button @click="userMenuOpen = !userMenuOpen" class="flex items-center w-full text-left px-2 py-2 space-x-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-200">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-cloak x-show="sidebarOpen" class="flex-1">
                        <div class="font-medium text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- User Menu Dropdown -->
                <div x-cloak x-show="userMenuOpen" @click.away="userMenuOpen = false" class="absolute bottom-full mb-2 left-0 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-100 dark:border-gray-700">
                    <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Profile') }}
                        </div>
                    </a>
                    
                    <button wire:click="logout" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Log Out') }}
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black bg-opacity-50 sm:hidden"></div>
</div>