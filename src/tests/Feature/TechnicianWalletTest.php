<?php

namespace Tests\Feature;

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Filament\Admin\Resources\TechnicianWithdrawalResource\Pages\ListTechnicianWithdrawals;
use App\Livewire\TechnicianWallet;
use App\Models\ServiceCategory;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\TechnicianProfile;
use App\Models\TechnicianWithdrawal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TechnicianWalletTest extends TestCase
{
    use RefreshDatabase;

    protected User $technician;
    protected User $customer;
    protected User $admin;
    protected ServiceCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = ServiceCategory::create([
            'name' => 'Perbaikan Pipa & Kran',
            'slug' => 'perbaikan-pipa-kran',
        ]);

        $this->technician = User::factory()->create([
            'name' => 'Bambang Teknisi',
            'role' => UserRole::TEKNISI,
            'phone' => '08123456789',
        ]);

        TechnicianProfile::create([
            'user_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'bio' => 'Teknisi pipa berpengalaman',
            'base_rate' => 75000,
            'is_available' => true,
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Rina Pelanggan',
            'role' => UserRole::PELANGGAN,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin Keuangan',
            'role' => UserRole::ADMIN,
        ]);
    }

    public function test_technician_can_view_wallet_page_with_calculated_balances(): void
    {
        // 1. Buat pesanan jasa selesai & komisi 95% = Rp71.250
        $order = ServiceOrder::create([
            'order_number' => 'SRV-TEST-001',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'total_service_fee' => 75000,
            'base_service_fee' => 75000,
            'distance_fee' => 0,
            'distance_km' => 3.5,
            'job_description' => 'Perbaikan kebocoran pipa',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceOrderStatus::SELESAI,
            'booking_date' => now(),
            'address' => 'Jl. Pengadegan No. 10',
            'latitude' => -6.175,
            'longitude' => 106.827,
        ]);

        $response = $this->actingAs($this->technician)->get('/teknisi/saldo');

        $response->assertStatus(200);
        $response->assertSee('Kelola Saldo &amp; Penarikan Dana', false);
        $response->assertSee('Rp71.250');
        $response->assertSee('Saldo Tersedia');
    }

    public function test_technician_can_submit_withdrawal_request_successfully(): void
    {
        $this->actingAs($this->technician);

        // Order dengan base_service_fee 100.000 -> komisi teknisi 95% = 95.000 otomatis tercatat
        ServiceOrder::create([
            'order_number' => 'SRV-TEST-002',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'total_service_fee' => 100000,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'distance_km' => 2.0,
            'job_description' => 'Perbaikan kebocoran pipa',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceOrderStatus::SELESAI,
            'booking_date' => now(),
            'address' => 'Jl. Sudirman 1',
            'latitude' => -6.175,
            'longitude' => 106.827,
        ]);

        // Submit penarikan dana Rp50.000 via Livewire
        Livewire::test(TechnicianWallet::class)
            ->set('amount', '50000')
            ->set('bank_name', 'BCA')
            ->set('account_number', '1234567890')
            ->set('account_holder_name', 'Bambang Teknisi')
            ->set('notes', 'Tolong transfer segera min')
            ->call('submitWithdrawal')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('technician_withdrawals', [
            'technician_id' => $this->technician->id,
            'amount' => 50000,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder_name' => 'Bambang Teknisi',
            'status' => 'menunggu',
        ]);

        // Sisa saldo tersedia sekarang harus Rp45.000 (95.000 - 50.000)
        $component = new TechnicianWallet();
        $this->assertEquals(45000, $component->getAvailableBalance());
    }

    public function test_technician_cannot_withdraw_more_than_available_balance(): void
    {
        $this->actingAs($this->technician);

        // Komisi 95% = Rp47.500
        ServiceOrder::create([
            'order_number' => 'SRV-TEST-003',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'total_service_fee' => 50000,
            'base_service_fee' => 50000,
            'distance_fee' => 0,
            'distance_km' => 1.0,
            'job_description' => 'Perbaikan kebocoran pipa',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceOrderStatus::SELESAI,
            'booking_date' => now(),
            'address' => 'Jl. Thamrin 1',
            'latitude' => -6.175,
            'longitude' => 106.827,
        ]);

        // Coba tarik Rp100.000 (melebihi saldo)
        Livewire::test(TechnicianWallet::class)
            ->set('amount', '100000')
            ->set('bank_name', 'BCA')
            ->set('account_number', '1234567890')
            ->set('account_holder_name', 'Bambang Teknisi')
            ->call('submitWithdrawal')
            ->assertHasErrors(['amount']);

        $this->assertEquals(0, TechnicianWithdrawal::count());
    }

    public function test_admin_can_approve_and_transfer_withdrawal(): void
    {
        $withdrawal = TechnicianWithdrawal::create([
            'technician_id' => $this->technician->id,
            'withdrawal_code' => TechnicianWithdrawal::generateCode(),
            'amount' => 75000,
            'bank_name' => 'BCA',
            'account_number' => '987654321',
            'account_holder_name' => 'Bambang Teknisi',
            'status' => 'menunggu',
        ]);

        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));

        Livewire::actingAs($this->admin)
            ->test(ListTechnicianWithdrawals::class)
            ->callTableAction('approve', $withdrawal, data: [
                'admin_notes' => 'TRF-BCA-SUCCESS-9871',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertEquals('berhasil', $withdrawal->fresh()->status);
        $this->assertEquals('TRF-BCA-SUCCESS-9871', $withdrawal->fresh()->admin_notes);
        $this->assertNotNull($withdrawal->fresh()->processed_at);
    }

    public function test_admin_rejection_restores_technician_balance(): void
    {
        ServiceOrder::create([
            'order_number' => 'SRV-TEST-004',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'total_service_fee' => 100000,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'distance_km' => 1.0,
            'job_description' => 'Perbaikan kebocoran pipa',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceOrderStatus::SELESAI,
            'booking_date' => now(),
            'address' => 'Jl. Diponegoro 5',
            'latitude' => -6.175,
            'longitude' => 106.827,
        ]);

        $withdrawal = TechnicianWithdrawal::create([
            'technician_id' => $this->technician->id,
            'withdrawal_code' => TechnicianWithdrawal::generateCode(),
            'amount' => 95000,
            'bank_name' => 'Mandiri',
            'account_number' => '000011112222',
            'account_holder_name' => 'Nama Salah',
            'status' => 'menunggu',
        ]);

        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));

        Livewire::actingAs($this->admin)
            ->test(ListTechnicianWithdrawals::class)
            ->callTableAction('reject', $withdrawal, data: [
                'admin_notes' => 'Nomor rekening tidak terdaftar di Bank Mandiri',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertEquals('ditolak', $withdrawal->fresh()->status);

        // Setelah ditolak, saldo teknisi kembali utuh Rp95.000
        $this->actingAs($this->technician);
        $component = new TechnicianWallet();
        $this->assertEquals(95000, $component->getAvailableBalance());
    }

    public function test_customer_or_unauthenticated_cannot_access_wallet(): void
    {
        $this->get('/teknisi/saldo')
            ->assertRedirect('/login');

        $this->actingAs($this->customer)
            ->get('/teknisi/saldo')
            ->assertForbidden();
    }
}
