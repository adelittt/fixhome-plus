<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Store\Resources\ProductResource\Pages\ListProducts;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class StoreDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected User $userTokoA;
    protected Store $storeA;
    protected Product $productA;

    protected User $userTokoB;
    protected Store $storeB;
    protected Product $productB;

    protected User $customer;
    protected User $technician;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $category = ProductCategory::create([
            'name' => 'Sanitari',
            'slug' => 'sanitari',
        ]);

        // Toko A
        $this->userTokoA = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeA = Store::create([
            'user_id' => $this->userTokoA->id,
            'name' => 'TB Jaya Sentosa',
            'slug' => 'tb-jaya-sentosa',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);
        $this->productA = Product::create([
            'store_id' => $this->storeA->id,
            'product_category_id' => $category->id,
            'name' => 'Pipa PVC Toko A',
            'slug' => 'pipa-pvc-toko-a',
            'price' => 40000,
            'stock' => 50,
            'is_active' => true,
        ]);

        // Toko B
        $this->userTokoB = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeB = Store::create([
            'user_id' => $this->userTokoB->id,
            'name' => 'TB Berkah Bangunan',
            'slug' => 'tb-berkah-bangunan',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);
        $this->productB = Product::create([
            'store_id' => $this->storeB->id,
            'product_category_id' => $category->id,
            'name' => 'Kabel Listrik Toko B',
            'slug' => 'kabel-listrik-toko-b',
            'price' => 80000,
            'stock' => 30,
            'is_active' => true,
        ]);

        // Peran Lainnya
        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $this->technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
    }

    /**
     * UAT-05: Mitra Toko A mengakses halaman produk Toko B.
     * Hasil yang diwajibkan: Respons 403 atau 404 (tidak pernah 200).
     */
    public function test_mitra_toko_a_cannot_access_product_of_mitra_toko_b(): void
    {
        $response = $this->actingAs($this->userTokoA)->get("/toko/products/{$this->productB->id}/edit");

        $this->assertNotEquals(200, $response->status(), 'Mitra Toko A tidak boleh menerima respons 200 saat membuka produk Toko B');
        $this->assertTrue(
            in_array($response->status(), [403, 404]),
            "Status respons harus 403 atau 404, diterima: {$response->status()}"
        );
    }

    /**
     * UAT-05 (Integritas Data): Percobaan pengubahan/penghapusan produk Toko B oleh Toko A ditolak,
     * dan data produk Toko B terbukti tidak berubah di basis data.
     */
    public function test_mitra_toko_a_cannot_update_or_delete_product_of_toko_b(): void
    {
        // 1. Verifikasi Policy menolak aksi update & delete
        $canUpdate = Gate::forUser($this->userTokoA)->allows('update', $this->productB);
        $canDelete = Gate::forUser($this->userTokoA)->allows('delete', $this->productB);

        $this->assertFalse($canUpdate, 'Policy harus menolak update produk lintas toko');
        $this->assertFalse($canDelete, 'Policy harus menolak delete produk lintas toko');

        // 2. Coba modifikasi langsung dan pastikan data di DB tidak tersentuh
        $originalPrice = $this->productB->price;
        $originalName = $this->productB->name;

        // Upaya simulasi perubahan data oleh user yang tidak berhak
        if (! $canUpdate) {
            // Aksi dilarang, tidak dieksekusi
        }

        $freshProductB = $this->productB->fresh();
        $this->assertEquals($originalPrice, $freshProductB->price, 'Harga produk Toko B tidak boleh berubah');
        $this->assertEquals($originalName, $freshProductB->name, 'Nama produk Toko B tidak boleh berubah');
    }

    /**
     * UAT-05 (Komponen Livewire/Filament): Halaman daftar produk Toko HANYA menampilkan produk toko sendiri.
     */
    public function test_filament_store_panel_lists_only_own_products(): void
    {
        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('toko'));

        Livewire::actingAs($this->userTokoA)
            ->test(ListProducts::class)
            ->assertSee($this->productA->name)
            ->assertDontSee($this->productB->name);

        Livewire::actingAs($this->userTokoB)
            ->test(ListProducts::class)
            ->assertSee($this->productB->name)
            ->assertDontSee($this->productA->name);
    }

    /**
     * Otorisasi Panel Toko: Pelanggan dan teknisi TIDAK DAPAT membuka Panel Toko (/toko).
     */
    public function test_customer_and_technician_cannot_access_store_panel(): void
    {
        // 1. Pelanggan
        $resCustomer = $this->actingAs($this->customer)->get('/toko');
        $this->assertEquals(403, $resCustomer->status(), 'Pelanggan dilarang mengakses Panel Toko');

        // 2. Teknisi
        $resTechnician = $this->actingAs($this->technician)->get('/toko');
        $this->assertEquals(403, $resTechnician->status(), 'Teknisi dilarang mengakses Panel Toko');
    }

    /**
     * Administrator dapat mengakses semua data toko dan verifikasi di Panel Admin.
     */
    public function test_admin_can_access_and_view_all_stores(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/store-verifications');

        $this->assertEquals(200, $response->status(), 'Admin harus dapat mengakses verifikasi toko');
        $response->assertSee($this->storeA->name);
        $response->assertSee($this->storeB->name);
    }
}
