<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Inventory\Index;
use App\Livewire\Inventory\Add;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Base inventory route that redirects to index
    Route::get('/inventory', function () {
        return redirect()->route('inventory.index');
    })->name('inventory');

    // Index API ROUTE (GET)
    Route::get('/inventory/index', Index::class)->name('inventory.index');

    // Route for adding a product
    Route::get('/inventory/addproduct', Add::class)->name('inventory.addproduct');

    // Route for editing a product
    Route::get('/inventory/edit/{id}', App\Livewire\Inventory\Edit::class)->name('inventory.edit');

    // INDEX SALES
    Route::get('/sales', App\Livewire\Sales\Index::class)->name('sales.index')->middleware(['auth']);
});

// Sales Routes:
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route for Sales Index
    Route::get('/sales', App\Livewire\Sales\Index::class)->name('sales.index')->middleware(['auth']);
    // Route for adding Sales
    Route::get('/sales/add', App\Livewire\Sales\Add::class)->name('sales.add');
    // Route for Editing Sales
    Route::get('/sales/{id}/edit', App\Livewire\Sales\Edit::class)->name('sales.edit');
    // Route for Viewing Sales
    Route::get('/sales/view/{id}', App\Livewire\Sales\View::class)->name('sales.view');
});


// Customers Routes:
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route for Customer Index
    Route::get('/customers', App\Livewire\Customers\Index::class)->name('customers.index')->middleware(['auth']);
    // Route for adding a Customer
    Route::get('/customers/add', App\Livewire\Customers\Add::class)->name('customers.add');
    // Route for editing customer
    Route::get('/customers/edit/{id}', App\Livewire\Customers\Edit::class)->name('customers.edit');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
