<?php

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Livewire\MaterialRecommendationManager;
use App\Livewire\TechnicianDashboard;
use App\Models\ServiceCategory;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('dasbor teknisi menampilkan panel kerja, metrik pendapatan 95 persen, dan tab navigasi', function () {
    $technician = User::factory()->create(['role' => UserRole::TEKNISI]);
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);

    $category = ServiceCategory::create([
        'name' => 'Perbaikan Atap & Genteng',
        'slug' => 'atap-genteng',
        'is_active' => true,
    ]);

    $order = ServiceOrder::create([
        'order_number' => 'SRV-TEST-DASH-01',
        'customer_id' => $customer->id,
        'technician_id' => $technician->id,
        'service_category_id' => $category->id,
        'job_description' => 'Genteng bocor di ruang tengah',
        'scheduled_at' => now()->addDay(),
        'address' => 'Jl. Dahlia No. 10',
        'latitude' => -6.1800,
        'longitude' => 106.7800,
        'distance_km' => 3.2,
        'base_service_fee' => 100000,
        'distance_fee' => 0,
        'total_service_fee' => 100000,
        'status' => ServiceOrderStatus::MENUNGGU_TEKNISI,
    ]);

    Livewire::actingAs($technician)
        ->test(TechnicianDashboard::class)
        ->assertSee('Dasbor Pekerjaan Teknisi')
        ->assertSee('Panel Teknisi FixHome Plus')
        ->assertSee('Pesanan Masuk')
        ->assertSee('Sedang Berjalan')
        ->assertSee('Selesai')
        ->assertSee('Riwayat Batal / Tolak')
        ->assertSee('Pendapatan Bersih (95%)')
        ->assertSee('Terima Pesanan')
        ->assertSee('Tolak')
        ->assertSee('#SRV-TEST-DASH-01');
});

test('dasbor teknisi menampilkan komisi jasa 5 persen dan hak 95 persen secara transparan', function () {
    $technician = User::factory()->create(['role' => UserRole::TEKNISI]);
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);

    $category = ServiceCategory::create([
        'name' => 'Perbaikan AC',
        'slug' => 'perbaikan-ac',
        'is_active' => true,
    ]);

    $order = ServiceOrder::create([
        'order_number' => 'SRV-TEST-COMM-02',
        'customer_id' => $customer->id,
        'technician_id' => $technician->id,
        'service_category_id' => $category->id,
        'job_description' => 'Cuci AC 1 PK',
        'scheduled_at' => now()->subDay(),
        'address' => 'Jl. Kenanga No. 8',
        'latitude' => -6.1900,
        'longitude' => 106.7900,
        'distance_km' => 2.0,
        'base_service_fee' => 80000,
        'distance_fee' => 0,
        'total_service_fee' => 80000,
        'status' => ServiceOrderStatus::SELESAI,
    ]);

    ServiceCommission::create([
        'service_order_id' => $order->id,
        'technician_id' => $technician->id,
        'gross_amount' => 80000,
        'commission_rate' => 0.05,
        'platform_commission' => 4000,
        'technician_earning' => 76000,
        'status' => 'tercatat',
    ]);

    Livewire::actingAs($technician)
        ->test(TechnicianDashboard::class)
        ->call('setTab', 'selesai')
        ->assertSee('Dasar Jasa:')
        ->assertSee('Komisi Platform (5%):')
        ->assertSee('-Rp4.000')
        ->assertSee('Bersih Diterima (95%):')
        ->assertSee('Rp76.000')
        ->assertSee('Komisi Tercatat (AB-001)');
});

test('form rekomendasi material teknisi memuat aturan AB-007 dan dapat menambah butir', function () {
    $technician = User::factory()->create(['role' => UserRole::TEKNISI]);
    $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);

    $category = ServiceCategory::create([
        'name' => 'Pengecatan Tembok',
        'slug' => 'pengecatan',
        'is_active' => true,
    ]);

    $order = ServiceOrder::create([
        'order_number' => 'SRV-TEST-REC-03',
        'customer_id' => $customer->id,
        'technician_id' => $technician->id,
        'service_category_id' => $category->id,
        'job_description' => 'Cat dinding kamar tidur',
        'scheduled_at' => now()->addDays(2),
        'address' => 'Jl. Anggrek No. 15',
        'latitude' => -6.1750,
        'longitude' => 106.7650,
        'distance_km' => 4.0,
        'base_service_fee' => 150000,
        'distance_fee' => 0,
        'total_service_fee' => 150000,
        'status' => ServiceOrderStatus::DITERIMA,
    ]);

    Livewire::actingAs($technician)
        ->test(MaterialRecommendationManager::class, ['order' => $order])
        ->assertSee('Susun Rekomendasi Material')
        ->assertSee('Prinsip Rekomendasi (AB-007)')
        ->assertSee('Tambah Butir Material')
        ->call('addItem')
        ->assertSee('Butir Material #2');
});
