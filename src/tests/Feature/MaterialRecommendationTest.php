<?php

namespace Tests\Feature;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Livewire\MaterialRecommendationManager;
use App\Livewire\MaterialRecommendationViewer;
use App\Livewire\TechnicianDashboard;
use App\Models\MaterialRecommendation;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MaterialRecommendationTest extends TestCase
{
    use RefreshDatabase;

    protected User $technician;
    protected User $otherTechnician;
    protected User $customer;
    protected ServiceCategory $category;
    protected ServiceOrder $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $this->otherTechnician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);

        $this->category = ServiceCategory::create([
            'name' => 'Perbaikan Sanitasi & Pipa',
            'slug' => 'sanitasi-pipa',
        ]);

        $this->order = ServiceOrder::create([
            'order_number' => 'SRV-REC-TEST-01',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'job_description' => 'Pipa wastafel bocor dan kran patah',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Sudirman No. 12',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'distance_km' => 5.0,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'total_service_fee' => 100000,
            'status' => ServiceOrderStatus::DITERIMA,
            'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
        ]);
    }

    /**
     * Teknisi yang ditugaskan dapat menyusun butir rekomendasi material.
     */
    public function test_assigned_technician_can_add_material_recommendation(): void
    {
        Livewire::actingAs($this->technician)
            ->test(MaterialRecommendationManager::class, ['order' => $this->order])
            ->set('notes', 'Gunakan pipa tebal tahan tekanan air')
            ->set('items', [
                [
                    'material_name' => 'Pipa PVC AW 1/2 Inch',
                    'specification' => 'Panjang 4m SNI Rucika',
                    'quantity' => 2,
                    'unit' => 'batang',
                    'notes' => 'Pastikan tipe AW',
                ],
                [
                    'material_name' => 'Kran Wastafel Angsa',
                    'specification' => 'Stainless Steel SUS 304',
                    'quantity' => 1,
                    'unit' => 'pcs',
                    'notes' => '',
                ],
            ])
            ->call('saveRecommendation')
            ->assertHasNoErrors()
            ->assertDispatched('recommendation-updated');

        $this->assertDatabaseHas('material_recommendations', [
            'service_order_id' => $this->order->id,
            'notes' => 'Gunakan pipa tebal tahan tekanan air',
            'revision_count' => 0,
        ]);

        $this->assertDatabaseHas('material_recommendation_items', [
            'material_name' => 'Pipa PVC AW 1/2 Inch',
            'quantity' => 2,
            'unit' => 'batang',
        ]);

        $this->assertDatabaseHas('material_recommendation_items', [
            'material_name' => 'Kran Wastafel Angsa',
            'quantity' => 1,
            'unit' => 'pcs',
        ]);
    }

    /**
     * Otorisasi AB-007: Teknisi lain atau pengguna lain tidak berhak membuat/mengubah rekomendasi material.
     */
    public function test_unauthorized_user_cannot_save_material_recommendation(): void
    {
        Livewire::actingAs($this->otherTechnician)
            ->test(MaterialRecommendationManager::class, ['order' => $this->order])
            ->set('items', [
                [
                    'material_name' => 'Pipa Ilegal',
                    'quantity' => 1,
                    'unit' => 'pcs',
                ],
            ])
            ->call('saveRecommendation')
            ->assertStatus(403);

        Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationManager::class, ['order' => $this->order])
            ->set('items', [
                [
                    'material_name' => 'Pipa Pelanggan',
                    'quantity' => 1,
                    'unit' => 'pcs',
                ],
            ])
            ->call('saveRecommendation')
            ->assertStatus(403);
    }

    /**
     * Teknisi dapat merevisi butir rekomendasi material dan revision_count bertambah.
     */
    public function test_technician_can_revise_material_recommendation(): void
    {
        // Buat rekomendasi awal
        $rec = MaterialRecommendation::create([
            'service_order_id' => $this->order->id,
            'notes' => 'Catatan awal',
            'revision_count' => 0,
        ]);
        $rec->items()->create([
            'material_name' => 'Pipa PVC',
            'quantity' => 1,
            'unit' => 'batang',
        ]);

        // Teknisi memperbarui rekomendasi
        Livewire::actingAs($this->technician)
            ->test(MaterialRecommendationManager::class, ['order' => $this->order])
            ->set('notes', 'Catatan revisi: tambah lem pipa')
            ->set('items', [
                [
                    'material_name' => 'Pipa PVC AW 1/2 Inch',
                    'specification' => 'SNI',
                    'quantity' => 2,
                    'unit' => 'batang',
                    'notes' => '',
                ],
                [
                    'material_name' => 'Lem Pipa PVC',
                    'specification' => 'Tube 45 gram',
                    'quantity' => 1,
                    'unit' => 'tube',
                    'notes' => '',
                ],
            ])
            ->call('saveRecommendation')
            ->assertHasNoErrors();

        $recFresh = $rec->fresh();
        $this->assertEquals(1, $recFresh->revision_count);
        $this->assertEquals('Catatan revisi: tambah lem pipa', $recFresh->notes);
        $this->assertCount(2, $recFresh->items);
    }

    /**
     * Pelanggan dapat memilih opsi "Lewati Marketplace" (PRD 4.2 & AB-006).
     */
    public function test_customer_can_choose_lewati_marketplace(): void
    {
        Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationViewer::class, ['order' => $this->order])
            ->call('chooseOption', 'lewati')
            ->assertHasNoErrors();

        $this->assertEquals(MarketplaceChoice::LEWATI, $this->order->fresh()->marketplace_choice);
    }

    /**
     * Pelanggan dapat memilih opsi "Lihat Marketplace".
     */
    public function test_customer_can_choose_lihat_marketplace(): void
    {
        Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationViewer::class, ['order' => $this->order])
            ->call('chooseOption', 'lihat')
            ->assertHasNoErrors();

        $this->assertEquals(MarketplaceChoice::LIHAT, $this->order->fresh()->marketplace_choice);
    }

    /**
     * Sesuai PK-17: Pelanggan dapat mengganti pilihan selama pesanan jasa belum selesai.
     */
    public function test_customer_can_toggle_choice_before_service_completed(): void
    {
        $viewer = Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationViewer::class, ['order' => $this->order]);

        // Pilih Lewati
        $viewer->call('chooseOption', 'lewati');
        $this->assertEquals(MarketplaceChoice::LEWATI, $this->order->fresh()->marketplace_choice);

        // Ubah jadi Lihat Marketplace
        $viewer->call('chooseOption', 'lihat');
        $this->assertEquals(MarketplaceChoice::LIHAT, $this->order->fresh()->marketplace_choice);

        // Ubah kembali jadi Lewati
        $viewer->call('chooseOption', 'lewati');
        $this->assertEquals(MarketplaceChoice::LEWATI, $this->order->fresh()->marketplace_choice);
    }

    /**
     * Pilihan tidak dapat diubah lagi jika pesanan sudah selesai atau dibatalkan.
     */
    public function test_customer_cannot_toggle_choice_after_service_completed(): void
    {
        $this->order->update([
            'status' => ServiceOrderStatus::SELESAI,
            'marketplace_choice' => MarketplaceChoice::LEWATI,
        ]);

        Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationViewer::class, ['order' => $this->order])
            ->call('chooseOption', 'lihat')
            ->assertSee('Status pesanan jasa sudah final dan tidak dapat diubah lagi.');

        $this->assertEquals(MarketplaceChoice::LEWATI, $this->order->fresh()->marketplace_choice);
    }

    /**
     * Validasi K-4 & UAT-02:
     * Pelanggan memilih "Lewati Marketplace" -> alur pesanan jasa tetap berjalan mulus,
     * teknisi menyelesaikan pesanan -> komisi jasa 5% dicatat, TIDAK ADA komisi marketplace yang dicatat.
     */
    public function test_order_completion_with_lewati_marketplace_generates_zero_marketplace_commission(): void
    {
        // 1. Rekomendasi dibuat
        $rec = MaterialRecommendation::create([
            'service_order_id' => $this->order->id,
            'notes' => 'Siapkan material sendiri sesuai spesifikasi',
        ]);
        $rec->items()->create([
            'material_name' => 'Pipa PVC',
            'quantity' => 1,
            'unit' => 'batang',
        ]);

        // 2. Pelanggan memilih Lewati Marketplace
        Livewire::actingAs($this->customer)
            ->test(MaterialRecommendationViewer::class, ['order' => $this->order])
            ->call('chooseOption', 'lewati');

        $this->assertEquals(MarketplaceChoice::LEWATI, $this->order->fresh()->marketplace_choice);

        // 3. Pesanan berjalan (berlangsung)
        $this->order->update(['status' => ServiceOrderStatus::BERLANGSUNG]);

        // 4. Teknisi menyelesaikan pesanan jasa
        Livewire::actingAs($this->technician)
            ->test(TechnicianDashboard::class)
            ->call('completeOrder', $this->order->id)
            ->assertHasNoErrors();

        $fresh = $this->order->fresh();
        $this->assertEquals(ServiceOrderStatus::SELESAI, $fresh->status);
        $this->assertNotNull($fresh->completed_at);

        // 5. Komisi jasa 5% tercatat benar
        $this->assertDatabaseHas('service_commissions', [
            'service_order_id' => $this->order->id,
            'technician_id' => $this->technician->id,
            'gross_amount' => 100000,
            'platform_commission' => 5000,
            'technician_earning' => 95000,
        ]);

        // 6. Tidak ada catatan komisi marketplace sama sekali (K-4)
        $this->assertDatabaseEmpty('marketplace_commissions');
    }
}
