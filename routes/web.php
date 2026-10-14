<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [OrderController::class, 'adminDashboard'])->name('dashboard');

    // Resource routes untuk Kelola Event (events.index, events.create, events.edit, dll)
    Route::resource('events', EventsController::class);

    // Alias URL jika user mengetik /admin/events atau /Admin/Events langsung di URL
    Route::get('/admin/events', fn () => redirect()->route('events.index'));
    Route::get('/Admin/Events', fn () => redirect()->route('events.index'));

    // Update status pesanan
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
