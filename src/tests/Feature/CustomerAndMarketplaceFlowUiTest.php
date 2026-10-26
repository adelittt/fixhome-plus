<?php

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Livewire\MaterialRecommendationViewer;
use App\Livewire\ServiceBookingForm;
use App\Models\MaterialRecommendation;
use App\Models\MaterialRecommendationItem;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\Store;
use App\Models\TechnicianProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('tombol Lewati Marketplace selalu tampil bersama Lihat Marketplace dan bersifat opsional', function () {
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
    $technician = User::factory()->create(['role' => UserRole::TEKNISI]);

    $category = ServiceCategory::create([
        'name' => 'Perbaikan Pipa Air',
        'slug' => 'pipa-air',
        'is_active' => true,
    ]);

    $order = ServiceOrder::create([
        'order_number' => 'SRV-TEST-EQUALITY-01',
        'customer_id' => $customer->id,
        'technician_id' => $technician->id,
        'service_category_id' => $category->id,
        'job_description' => 'Pipa wastafel bocor',
        'scheduled_at' => now()->addDay(),
        'address' => 'Jl. Kebon Jeruk No. 5',
        'latitude' => -6.1900,
        'longitude' => 106.7700,
        'distance_km' => 4.5,
        'base_service_fee' => 75000,
        'distance_fee' => 0,
        'total_service_fee' => 75000,
        'status' => ServiceOrderStatus::DITERIMA,
        'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
    ]);

    $recommendation = MaterialRecommendation::create([
        'service_order_id' => $order->id,
        'technician_id' => $technician->id,
        'notes' => 'Gunakan pipa PVC ukuran 1/2 inch',
    ]);

    MaterialRecommendationItem::create([
        'material_recommendation_id' => $recommendation->id,
        'material_name' => 'Pipa PVC AW 1/2 Inch',
        'quantity' => 2,
        'unit' => 'Batang',
        'specification' => 'Merk Rucika atau setara',
    ]);

    Livewire::actingAs($customer)
        ->test(MaterialRecommendationViewer::class, ['order' => $order])
        ->assertSee('Lihat Marketplace')
        ->assertSee('Lewati Marketplace')
        ->assertSee('Pembelian material bersifat opsional')
        ->call('chooseOption', 'lewati')
        ->assertSee('Menyediakan Material Sendiri (Lewati Marketplace)');

    $order->refresh();
    expect($order->marketplace_choice)->toBe(MarketplaceChoice::LEWATI);
    expect($order->status)->toBe(ServiceOrderStatus::DITERIMA);
});

test('formulir pemesanan jasa menampilkan stepper dan rincian biaya simulasi', function () {
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
    $techUser = User::factory()->create(['role' => UserRole::TEKNISI, 'name' => 'Bambang Sudarsono']);

    $category = ServiceCategory::create([
        'name' => 'Perbaikan Listrik',
        'slug' => 'listrik',
        'is_active' => true,
    ]);

    TechnicianProfile::create([
        'user_id' => $techUser->id,
        'service_category_id' => $category->id,
        'bio' => 'Teknisi listrik bersertifikat',
        'address' => 'Tangerang',
        'latitude' => -6.1700,
        'longitude' => 106.6300,
        'verification_status' => VerificationStatus::DISETUJUI,
        'is_available' => true,
    ]);

    $response = $this->actingAs($customer)->get("/pesan-jasa/{$techUser->id}");

    $response->assertStatus(200);
    $response->assertSee('Form Pemesanan Jasa');
    $response->assertSee('Langkah 1');
    $response->assertSee('Langkah 2');
    $response->assertSee('Langkah 3');
    $response->assertSee('Rincian Biaya Transparan');
    $response->assertSee('Mode Simulasi');
});

test('keranjang dan checkout menampilkan pengelompokan per toko dengan token desain', function () {
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
    $tokoUser = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
    $store = Store::create([
        'user_id' => $tokoUser->id,
        'name' => 'Toko Material Sentosa',
        'slug' => 'toko-material-sentosa',
        'address' => 'Jl. Merak No. 12',
        'phone' => '081234567890',
        'verification_status' => VerificationStatus::DISETUJUI,
        'is_active' => true,
    ]);

    $category = \App\Models\ProductCategory::create([
        'name' => 'Cat & Pelapis',
        'slug' => 'cat-pelapis',
    ]);

    $product = \App\Models\Product::create([
        'store_id' => $store->id,
        'product_category_id' => $category->id,
        'name' => 'Cat Dulux 5kg',
        'slug' => 'cat-dulux-5kg',
        'price' => 150000,
        'stock' => 10,
        'is_active' => true,
    ]);

    \App\Models\CartItem::create([
        'customer_id' => $customer->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    Livewire::actingAs($customer)
        ->test(\App\Livewire\CartManager::class)
        ->assertSee('Keranjang Belanja Material')
        ->assertSee('Toko Material Sentosa')
        ->assertSee('Cat Dulux 5kg')
        ->assertSee('Subtotal: Rp300.000')
        ->assertSee('Lanjut ke Checkout');

    Livewire::actingAs($customer)
        ->test(\App\Livewire\CheckoutPage::class)
        ->assertSee('Checkout Pemesanan Material')
        ->assertSee('Toko Material Sentosa')
        ->assertSee('Pembayaran Mode Simulasi')
        ->assertSee('Bayar & Buat Pesanan (Simulasi)');
});
