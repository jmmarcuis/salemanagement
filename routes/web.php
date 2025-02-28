<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Inventory\Index;
use App\Livewire\Inventory\Add; // Assuming you have a Livewire component for adding products

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
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
