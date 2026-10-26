<?php

namespace Tests\Feature;

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Livewire\TechnicianDashboard;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TechnicianOrderManagementTest extends TestCase
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
            'name' => 'Instalasi Listrik',
            'slug' => 'instalasi-listrik',
        ]);

        $this->order = ServiceOrder::create([
            'order_number' => 'SRV-TEST-TECH-MGT',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'job_description' => 'Ganti MCB listrik 10A korslet',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Kebon Jeruk No. 10',
            'latitude' => -6.1683,
            'longitude' => 106.7588,
            'distance_km' => 4.2,
            'base_service_fee' => 85000,
            'distance_fee' => 0,
            'total_service_fee' => 85000,
            'status' => ServiceOrderStatus::MENUNGGU_TEKNISI,
        ]);
    }

    /**
     * Teknisi menerima pesanan masuk -> status berubah menjadi diterima.
     */
    public function test_technician_can_accept_incoming_order(): void
    {
        Livewire::actingAs($this->technician)
            ->test(TechnicianDashboard::class)
            ->call('acceptOrder', $this->order->id)
            ->assertHasNoErrors();

        $this->assertEquals(ServiceOrderStatus::DITERIMA, $this->order->fresh()->status);
    }

    /**
     * Teknisi menolak pesanan masuk -> status berubah menjadi ditolak.
     */
    public function test_technician_can_reject_incoming_order(): void
    {
        Livewire::actingAs($this->technician)
            ->test(TechnicianDashboard::class)
            ->call('rejectOrder', $this->order->id, 'Jadwal teknisi bentrok')
            ->assertHasNoErrors();

        $fresh = $this->order->fresh();
        $this->assertEquals(ServiceOrderStatus::DITOLAK, $fresh->status);
        $this->assertEquals('Jadwal teknisi bentrok', $fresh->cancellation_reason);
    }

    /**
     * Alur siklus pengerjaan teknisi: diterima -> berlangsung -> selesai + pencatatan komisi 5%.
     */
    public function test_technician_work_lifecycle_and_commission_recording(): void
    {
        $this->order->update(['status' => ServiceOrderStatus::DITERIMA]);

        // 1. Mulai pengerjaan
        Livewire::actingAs($this->technician)
            ->test(TechnicianDashboard::class)
            ->call('startOrder', $this->order->id);

        $this->assertEquals(ServiceOrderStatus::BERLANGSUNG, $this->order->fresh()->status);

        // 2. Selesaikan pengerjaan
        Livewire::actingAs($this->technician)
            ->test(TechnicianDashboard::class)
            ->call('completeOrder', $this->order->id);

        $fresh = $this->order->fresh();
        $this->assertEquals(ServiceOrderStatus::SELESAI, $fresh->status);
        $this->assertNotNull($fresh->completed_at);

        // 3. Verifikasi pencatatan komisi jasa 5% otomatis di tabel service_commissions
        $this->assertDatabaseHas('service_commissions', [
            'service_order_id' => $this->order->id,
            'technician_id' => $this->technician->id,
            'gross_amount' => 85000,
            'platform_commission' => 4250, // 5% dari 85.000
            'technician_earning' => 80750, // 95% dari 85.000
        ]);
    }

    /**
     * Otorisasi: Teknisi lain TIDAK BISA menerima atau menolak pesanan milik teknisi ini.
     */
    public function test_other_technician_cannot_manage_orders_not_assigned_to_them(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::actingAs($this->otherTechnician)
            ->test(TechnicianDashboard::class)
            ->call('acceptOrder', $this->order->id);
    }
}
