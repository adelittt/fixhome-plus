<?php

namespace Tests\Feature;

use App\Enums\ProductOrderStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Store\Widgets\LatestStoreOrdersWidget;
use App\Filament\Store\Widgets\StoreLowStockProductsWidget;
use App\Filament\Store\Widgets\StoreStatsOverviewWidget;
use App\Filament\Store\Widgets\StoreWelcomeWidget;
use App\Models\MarketplaceCommission;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $userTokoA;
    protected Store $storeA;
    protected ProductCategory $category;

    protected User $userTokoB;
    protected Store $storeB;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = ProductCategory::create([
            'name' => 'Bahan Bangunan',
            'slug' => 'bahan-bangunan',
        ]);

        // Toko A
        $this->userTokoA = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => UserRole::MITRA_TOKO,
        ]);
        $this->storeA = Store::create([
            'user_id' => $this->userTokoA->id,
            'name' => 'TB Berkah Makmur',
            'slug' => 'tb-berkah-makmur',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Jakarta Barat',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);

        // Toko B
        $this->userTokoB = User::factory()->create([
            'name' => 'Joko Anwar',
            'role' => UserRole::MITRA_TOKO,
        ]);
        $this->storeB = Store::create([
            'user_id' => $this->userTokoB->id,
            'name' => 'TB Sumber Rejeki',
            'slug' => 'tb-sumber-rejeki',
            'phone' => '089876543210',
            'address' => 'Jl. Sudirman No. 12, Jakarta Selatan',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Siti Pelanggan',
            'role' => UserRole::PELANGGAN,
        ]);
    }

    public function test_mitra_toko_can_view_store_dashboard_and_widgets(): void
    {
        $response = $this->actingAs($this->userTokoA)->get('/toko');

        $response->assertStatus(200);
        $response->assertSee('Portal Mitra Toko Material Bangunan');
        $response->assertSee('TB Berkah Makmur');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Jl. Merdeka No. 45, Jakarta Barat');
        $response->assertSee('081234567890');
    }

    public function test_store_welcome_widget_renders_cleanly(): void
    {
        $this->actingAs($this->userTokoA);

        Livewire::test(StoreWelcomeWidget::class)
            ->assertStatus(200)
            ->assertSee('TB Berkah Makmur')
            ->assertSee('Tambah Produk Material')
            ->assertSee('Kelola Pesanan Masuk');
    }

    public function test_store_dashboard_displays_isolated_stats_and_commissions(): void
    {
        $this->actingAs($this->userTokoA);

        // Order Toko A (Selesai, Pendapatan 98% = 98.000 otomatis tercatat oleh ProductOrderObserver)
        ProductOrder::create([
            'order_number' => 'ORD-TK-001',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 100000,
            'shipping_fee' => 15000,
            'grand_total' => 115000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        // Order Toko B (Pendapatan 98% = 490.000 otomatis tercatat oleh ProductOrderObserver)
        ProductOrder::create([
            'order_number' => 'ORD-TK-002',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 500000,
            'shipping_fee' => 20000,
            'grand_total' => 520000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        // Cek widget statistik Toko A
        Livewire::actingAs($this->userTokoA)
            ->test(StoreStatsOverviewWidget::class)
            ->assertStatus(200)
            ->assertSee('Rp98.000')
            ->assertSee('Dari 1 pesanan selesai (AB-003)')
            ->assertDontSee('Rp490.000');
    }

    public function test_latest_store_orders_widget_isolates_orders_by_store(): void
    {
        $this->actingAs($this->userTokoA);

        // Order Toko A
        ProductOrder::create([
            'order_number' => 'ORD-EXCLUSIVE-A',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 75000,
            'shipping_fee' => 12000,
            'grand_total' => 87000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        // Order Toko B
        ProductOrder::create([
            'order_number' => 'ORD-SECRET-B',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 250000,
            'shipping_fee' => 15000,
            'grand_total' => 265000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        Livewire::test(LatestStoreOrdersWidget::class)
            ->assertStatus(200)
            ->assertSee('ORD-EXCLUSIVE-A')
            ->assertDontSee('ORD-SECRET-B');
    }

    public function test_store_low_stock_products_widget_displays_critical_inventory(): void
    {
        $this->actingAs($this->userTokoA);

        Product::create([
            'store_id' => $this->storeA->id,
            'product_category_id' => $this->category->id,
            'name' => 'Semen Gresik 50kg',
            'slug' => 'semen-gresik-50kg',
            'price' => 65000,
            'stock' => 4,
            'is_active' => true,
        ]);

        Product::create([
            'store_id' => $this->storeB->id,
            'product_category_id' => $this->category->id,
            'name' => 'Cat Tembok Toko B',
            'slug' => 'cat-tembok-toko-b',
            'price' => 120000,
            'stock' => 2,
            'is_active' => true,
        ]);

        Livewire::test(StoreLowStockProductsWidget::class)
            ->assertStatus(200)
            ->assertSee('Semen Gresik 50kg')
            ->assertSee('4 unit (Kritis)')
            ->assertDontSee('Cat Tembok Toko B');
    }

    public function test_unauthenticated_or_customer_cannot_access_store_dashboard(): void
    {
        $this->get('/toko')
            ->assertRedirect('/login');

        $this->actingAs($this->customer)
            ->get('/toko')
            ->assertForbidden();
    }
}
