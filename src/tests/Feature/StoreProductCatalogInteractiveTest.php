<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Store\Resources\ProductResource;
use App\Filament\Store\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Store\Resources\ProductResource\Widgets\ProductOverviewWidget;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreProductCatalogInteractiveTest extends TestCase
{
    use RefreshDatabase;

    protected User $userToko;
    protected Store $store;
    protected ProductCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = ProductCategory::create([
            'name' => 'Pipa & Sanitari',
            'slug' => 'pipa-sanitari',
        ]);

        $this->userToko = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->store = Store::create([
            'user_id' => $this->userToko->id,
            'name' => 'TB Mandiri Bangunan',
            'slug' => 'tb-mandiri-bangunan',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);

        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('toko'));
    }

    public function test_mitra_toko_sees_overview_widgets_on_product_catalog_page(): void
    {
        $this->actingAs($this->userToko);

        Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $this->category->id,
            'name' => 'Pipa PVC Wavin AW 1/2 Inch',
            'slug' => 'pipa-pvc-wavin-aw-12-inch',
            'price' => 38000,
            'stock' => 50,
            'is_active' => true,
        ]);

        Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $this->category->id,
            'name' => 'Lem Pipa Isarplas',
            'slug' => 'lem-pipa-isarplas',
            'price' => 12500,
            'stock' => 8,
            'is_active' => true,
        ]);

        // Total inventory value = (38.000 * 50) + (12.500 * 8) = 1.900.000 + 100.000 = 2.000.000
        Livewire::test(ProductOverviewWidget::class)
            ->assertStatus(200)
            ->assertSee('Total Katalog')
            ->assertSee('2 Produk')
            ->assertSee('Rp2.000.000')
            ->assertSee('1 Produk'); // low stock (8 <= 15)
    }

    public function test_mitra_toko_can_use_interactive_tabs_on_product_list(): void
    {
        $this->actingAs($this->userToko);

        $safeProduct = Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $this->category->id,
            'name' => 'Semen Padang 50kg',
            'slug' => 'semen-padang-50kg',
            'price' => 65000,
            'stock' => 40,
            'is_active' => true,
        ]);

        $lowProduct = Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $this->category->id,
            'name' => 'Kuas Cat 2 Inch',
            'slug' => 'kuas-cat-2-inch',
            'price' => 15000,
            'stock' => 5,
            'is_active' => true,
        ]);

        Livewire::test(ListProducts::class)
            ->assertStatus(200)
            ->assertSee('Semua Material')
            ->assertSee('Stok Aman')
            ->assertSee('Stok Menipis')
            ->assertSee('Semen Padang 50kg')
            ->assertSee('Kuas Cat 2 Inch')
            ->set('activeTab', 'low_stock')
            ->assertSee('Kuas Cat 2 Inch')
            ->assertDontSee('Semen Padang 50kg');
    }

    public function test_mitra_toko_can_quick_restock_product_from_table(): void
    {
        $this->actingAs($this->userToko);

        $product = Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $this->category->id,
            'name' => 'Stop Kran Kuningan Onda',
            'slug' => 'stop-kran-kuningan-onda',
            'price' => 55000,
            'stock' => 10,
            'is_active' => true,
        ]);

        Livewire::test(ListProducts::class)
            ->callTableAction('quick_stock', $product, data: [
                'stock' => 85,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertEquals(85, $product->fresh()->stock);
    }
}
