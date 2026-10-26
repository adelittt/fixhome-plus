<?php

namespace Tests\Feature;

use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Store\Resources\ProductOrderResource;
use App\Livewire\CartManager;
use App\Livewire\CheckoutPage;
use App\Livewire\CustomerProductOrders;
use App\Livewire\MarketplaceCatalog;
use App\Livewire\ProductDetail;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\Store;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MarketplaceMultiStoreTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $tokoUserA;
    protected User $tokoUserB;
    protected Store $storeA;
    protected Store $storeB;
    protected ProductCategory $categoryPipa;
    protected ProductCategory $categorySemen;
    protected Product $productA1;
    protected Product $productA2;
    protected Product $productB1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);

        // Toko A (Jakarta Barat)
        $this->tokoUserA = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeA = Store::create([
            'user_id' => $this->tokoUserA->id,
            'name' => 'Toko Material Sentosa Jaya',
            'slug' => 'toko-material-sentosa-jaya',
            'address' => 'Jl. Panjang No. 15, Kebon Jeruk, Jakarta Barat',
            'phone' => '081211112222',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        // Toko B (Tangerang)
        $this->tokoUserB = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeB = Store::create([
            'user_id' => $this->tokoUserB->id,
            'name' => 'TB Berkah Bangunan',
            'slug' => 'tb-berkah-bangunan',
            'address' => 'Jl. Raya Serpong No. 88, Tangerang',
            'phone' => '081333334444',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        // Kategori Produk
        $this->categoryPipa = ProductCategory::create([
            'name' => 'Pipa & Sanitasi',
            'slug' => 'pipa-sanitasi',
        ]);
        $this->categorySemen = ProductCategory::create([
            'name' => 'Semen & Pasir',
            'slug' => 'semen-pasir',
        ]);

        // Produk Toko A
        $this->productA1 = Product::create([
            'store_id' => $this->storeA->id,
            'product_category_id' => $this->categoryPipa->id,
            'name' => 'Pipa PVC AW 1/2 Inch Rucika',
            'slug' => 'pipa-pvc-aw-half-inch-rucika',
            'description' => 'Pipa PVC SNI panjang 4 meter',
            'price' => 35000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $this->productA2 = Product::create([
            'store_id' => $this->storeA->id,
            'product_category_id' => $this->categoryPipa->id,
            'name' => 'Lem Pipa PVC Isarplas 45g',
            'slug' => 'lem-pipa-pvc-isarplas-45g',
            'description' => 'Lem perekat pipa tahan air',
            'price' => 12000,
            'stock' => 100,
            'is_active' => true,
        ]);

        // Produk Toko B
        $this->productB1 = Product::create([
            'store_id' => $this->storeB->id,
            'product_category_id' => $this->categorySemen->id,
            'name' => 'Semen Tiga Roda 40kg',
            'slug' => 'semen-tiga-roda-40kg',
            'description' => 'Semen Portland berkualitas tinggi',
            'price' => 70000,
            'stock' => 30,
            'is_active' => true,
        ]);
    }

    /**
     * Pelanggan dapat menelusuri katalog produk aktif dari toko yang telah diverifikasi (AB-011).
     */
    public function test_customer_can_view_active_products_from_verified_stores(): void
    {
        // Toko C yang belum diverifikasi
        $unverifiedStore = Store::create([
            'user_id' => User::factory()->create(['role' => UserRole::MITRA_TOKO])->id,
            'name' => 'Toko Bodong',
            'slug' => 'toko-bodong',
            'verification_status' => VerificationStatus::MENUNGGU,
            'is_active' => true,
        ]);
        $unverifiedProduct = Product::create([
            'store_id' => $unverifiedStore->id,
            'product_category_id' => $this->categoryPipa->id,
            'name' => 'Pipa Ilegal Tanpa Izin',
            'slug' => 'pipa-ilegal',
            'price' => 10000,
            'stock' => 10,
            'is_active' => true,
        ]);

        Livewire::test(MarketplaceCatalog::class)
            ->assertSee('Pipa PVC AW 1/2 Inch Rucika')
            ->assertSee('Semen Tiga Roda 40kg')
            ->assertDontSee('Pipa Ilegal Tanpa Izin');
    }

    /**
     * Pelanggan dapat melakukan pencarian dan filter kategori di katalog.
     */
    public function test_customer_can_search_and_filter_products(): void
    {
        Livewire::test(MarketplaceCatalog::class)
            ->set('search', 'Semen')
            ->assertSee('Semen Tiga Roda 40kg')
            ->assertDontSee('Pipa PVC AW 1/2 Inch Rucika')
            ->set('search', '')
            ->set('categoryId', $this->categoryPipa->id)
            ->assertSee('Pipa PVC AW 1/2 Inch Rucika')
            ->assertDontSee('Semen Tiga Roda 40kg');
    }

    /**
     * Pelanggan dapat menambahkan produk ke keranjang belanja dari katalog dan halaman detail.
     */
    public function test_customer_can_add_products_to_cart(): void
    {
        // 1. Tambah dari katalog
        Livewire::actingAs($this->customer)
            ->test(MarketplaceCatalog::class)
            ->call('addToCart', $this->productA1->id, 2)
            ->assertHasNoErrors()
            ->assertDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'customer_id' => $this->customer->id,
            'product_id' => $this->productA1->id,
            'quantity' => 2,
        ]);

        // 2. Tambah dari halaman detail produk
        Livewire::actingAs($this->customer)
            ->test(ProductDetail::class, ['id' => $this->productB1->id])
            ->set('quantity', 3)
            ->call('addToCart', false)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cart_items', [
            'customer_id' => $this->customer->id,
            'product_id' => $this->productB1->id,
            'quantity' => 3,
        ]);
    }

    /**
     * Keranjang multi-toko mengelompokkan item berdasarkan Toko Mitra dengan ongkir masing-masing (AB-001, AB-010).
     */
    public function test_cart_groups_items_by_store_with_individual_shipping_fees(): void
    {
        CartItem::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->productA1->id, // 2 x 35.000 = 70.000 (Toko A)
            'quantity' => 2,
        ]);
        CartItem::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->productA2->id, // 1 x 12.000 = 12.000 (Toko A)
            'quantity' => 1,
        ]);
        CartItem::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->productB1->id, // 3 x 70.000 = 210.000 (Toko B)
            'quantity' => 3,
        ]);

        Livewire::actingAs($this->customer)
            ->test(CartManager::class)
            ->assertSee('Toko Material Sentosa Jaya')
            ->assertSee('TB Berkah Bangunan')
            ->assertSee('Pipa PVC AW 1/2 Inch Rucika')
            ->assertSee('Semen Tiga Roda 40kg');
    }

    /**
     * Checkout Multi-Toko (AB-010):
     * Memecah keranjang menjadi pesanan terpisah untuk tiap Toko Mitra,
     * memotong stok secara atomik, dan membuat shipment serta payment.
     */
    public function test_multi_store_checkout_splits_into_separate_product_orders(): void
    {
        // Siapkan keranjang dengan 2 toko berbeda
        CartItem::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->productA1->id, // Toko A: 2 unit @ 35.000 = 70.000
            'quantity' => 2,
        ]);
        CartItem::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->productB1->id, // Toko B: 4 unit @ 70.000 = 280.000
            'quantity' => 4,
        ]);

        $initialStockA1 = $this->productA1->stock; // 50
        $initialStockB1 = $this->productB1->stock; // 30

        Livewire::actingAs($this->customer)
            ->test(CheckoutPage::class)
            ->set('recipientName', 'Ahmad Pelanggan')
            ->set('recipientPhone', '081299998888')
            ->set('deliveryAddress', 'Jl. Thamrin No. 45, Jakarta Pusat')
            ->call('placeOrder', true)
            ->assertRedirect(route('customer.product-orders'));

        // 1. Verifikasi TERDAPAT DUA ProductOrder terpisah (1 per Toko) sesuai AB-010
        $orders = ProductOrder::where('customer_id', $this->customer->id)->get();
        $this->assertCount(2, $orders);

        // Order Toko A
        $orderA = $orders->firstWhere('store_id', $this->storeA->id);
        $this->assertNotNull($orderA);
        $this->assertEquals(70000, $orderA->total_product_amount);
        $this->assertEquals(15000, $orderA->shipping_fee);
        $this->assertEquals(85000, $orderA->grand_total);
        $this->assertEquals(ProductOrderStatus::DIBAYAR, $orderA->status);
        $this->assertDatabaseHas('product_order_items', [
            'product_order_id' => $orderA->id,
            'product_id' => $this->productA1->id,
            'quantity' => 2,
            'subtotal' => 70000,
        ]);

        // Order Toko B
        $orderB = $orders->firstWhere('store_id', $this->storeB->id);
        $this->assertNotNull($orderB);
        $this->assertEquals(280000, $orderB->total_product_amount);
        $this->assertEquals(15000, $orderB->shipping_fee);
        $this->assertEquals(295000, $orderB->grand_total);
        $this->assertEquals(ProductOrderStatus::DIBAYAR, $orderB->status);
        $this->assertDatabaseHas('product_order_items', [
            'product_order_id' => $orderB->id,
            'product_id' => $this->productB1->id,
            'quantity' => 4,
            'subtotal' => 280000,
        ]);

        // 2. Verifikasi stok produk berkurang
        $this->assertEquals($initialStockA1 - 2, $this->productA1->fresh()->stock);
        $this->assertEquals($initialStockB1 - 4, $this->productB1->fresh()->stock);

        // 3. Verifikasi keranjang customer sudah kosong
        $this->assertDatabaseEmpty('cart_items');

        // 4. Verifikasi rekaman pembayaran & pengiriman terbuat untuk masing-masing toko
        $this->assertDatabaseHas('shipments', ['product_order_id' => $orderA->id, 'shipping_cost' => 15000]);
        $this->assertDatabaseHas('shipments', ['product_order_id' => $orderB->id, 'shipping_cost' => 15000]);
    }

    /**
     * Isolasi data toko (AB-009):
     * Toko A hanya melihat dan memproses pesanan untuk Toko A di panel toko, tidak bisa melihat Toko B.
     */
    public function test_mitra_toko_can_only_view_and_process_their_own_store_orders(): void
    {
        $orderA = ProductOrder::create([
            'order_number' => 'ORD-TEST-STORE-A',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 50000,
            'shipping_fee' => 15000,
            'grand_total' => 65000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $orderB = ProductOrder::create([
            'order_number' => 'ORD-TEST-STORE-B',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 90000,
            'shipping_fee' => 15000,
            'grand_total' => 105000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        // Login sebagai Toko A
        $this->actingAs($this->tokoUserA);
        Filament::setCurrentPanel(Filament::getPanel('toko'));

        $query = ProductOrderResource::getEloquentQuery();
        $results = $query->get();

        $this->assertTrue($results->contains('id', $orderA->id));
        $this->assertFalse($results->contains('id', $orderB->id));
    }

    /**
     * Pelanggan dapat mengonfirmasi pesanan produk yang telah dikirim menjadi selesai.
     */
    public function test_customer_can_confirm_delivered_product_order(): void
    {
        $order = ProductOrder::create([
            'order_number' => 'ORD-TEST-CONFIRM',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 50000,
            'shipping_fee' => 15000,
            'grand_total' => 65000,
            'status' => ProductOrderStatus::DIKIRIM,
        ]);

        $order->shipment()->create([
            'origin_address' => 'Toko A',
            'destination_address' => 'Rumah Pelanggan',
            'shipping_cost' => 15000,
            'status' => ShipmentStatus::DALAM_PERJALANAN,
        ]);

        Livewire::actingAs($this->customer)
            ->test(CustomerProductOrders::class)
            ->call('confirmDelivered', $order->id)
            ->assertHasNoErrors();

        $this->assertEquals(ProductOrderStatus::SELESAI, $order->fresh()->status);
        $this->assertEquals(ShipmentStatus::TERKIRIM, $order->shipment->fresh()->status);
    }
}
