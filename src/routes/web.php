<?php

use App\Livewire\CartManager;
use App\Livewire\CheckoutPage;
use App\Livewire\CustomerOrderList;
use App\Livewire\CustomerProductOrders;
use App\Livewire\MarketplaceCatalog;
use App\Livewire\OrderShipmentTracker;
use App\Livewire\ProductDetail;
use App\Livewire\ServiceBookingForm;
use App\Livewire\ServiceCatalog;
use App\Livewire\ServiceOrderDetail;
use App\Livewire\TechnicianDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

// Beranda & Katalog Layanan Jasa Publik
Route::get('/', ServiceCatalog::class)->name('home');
Route::get('/layanan', ServiceCatalog::class)->name('service.catalog');

// Marketplace Material Bangunan Publik (Fase 4)
Route::get('/marketplace', MarketplaceCatalog::class)->name('marketplace.index');
Route::get('/marketplace/produk/{id}', ProductDetail::class)->name('marketplace.product.detail');

// Alur Pemesanan Jasa & Marketplace Berotentikasi (Memerlukan Login)
Route::middleware(['auth'])->group(function () {
    // Alur Pemesanan Jasa
    Route::get('/pesan-jasa/{technicianId}', ServiceBookingForm::class)->name('service.book');
    Route::get('/pesanan-jasa/{orderId}', ServiceOrderDetail::class)->name('service.order.detail');
    Route::get('/pesanan-saya', CustomerOrderList::class)->name('customer.orders');

    // Alur Keranjang & Checkout Marketplace Material (Fase 4 & 5)
    Route::get('/keranjang', CartManager::class)->name('cart.index');
    Route::get('/checkout', CheckoutPage::class)->name('checkout.index');
    Route::get('/pesanan-produk', CustomerProductOrders::class)->name('customer.product-orders');
    Route::get('/pesanan-produk/{orderId}/lacak', OrderShipmentTracker::class)->name('customer.product-order.track');

    // Dasbor Khusus Teknisi
    Route::get('/teknisi/pesanan', TechnicianDashboard::class)->name('technician.dashboard');
});

use App\Livewire\CustomerDashboard;

Route::get('/dashboard', CustomerDashboard::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
