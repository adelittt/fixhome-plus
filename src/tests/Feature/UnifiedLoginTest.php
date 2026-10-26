<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class UnifiedLoginTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $tokoUser;
    protected Store $store;
    protected User $technician;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'email' => 'admin@fixhome.test',
        ]);

        $this->tokoUser = User::factory()->create([
            'role' => UserRole::MITRA_TOKO,
            'email' => 'toko@fixhome.test',
        ]);
        $this->store = Store::create([
            'user_id' => $this->tokoUser->id,
            'name' => 'TB Test',
            'slug' => 'tb-test',
            'verification_status' => VerificationStatus::DISETUJUI,
        ]);

        $this->technician = User::factory()->create([
            'role' => UserRole::TEKNISI,
            'email' => 'teknisi@fixhome.test',
        ]);

        $this->customer = User::factory()->create([
            'role' => UserRole::PELANGGAN,
            'email' => 'customer@fixhome.test',
        ]);
    }

    /**
     * Akses panel admin tanpa login otomatis dialihkan ke /login (Satu Pintu).
     */
    public function test_unauthenticated_user_accessing_admin_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /**
     * Akses panel toko tanpa login otomatis dialihkan ke /login (Satu Pintu).
     */
    public function test_unauthenticated_user_accessing_store_panel_is_redirected_to_login(): void
    {
        $response = $this->get('/toko');
        $response->assertRedirect('/login');
    }

    /**
     * Endpoint lama /admin/login dialihkan ke satu pintu login /login.
     */
    public function test_legacy_admin_login_url_redirects_to_unified_login(): void
    {
        $response = $this->get('/admin/login');
        $response->assertRedirect('/login');
    }

    /**
     * Endpoint lama /toko/login dialihkan ke satu pintu login /login.
     */
    public function test_legacy_store_login_url_redirects_to_unified_login(): void
    {
        $response = $this->get('/toko/login');
        $response->assertRedirect('/login');
    }

    /**
     * Login sebagai Admin melalui satu pintu dialihkan langsung ke /admin.
     */
    public function test_admin_logs_in_via_unified_door_and_redirects_to_admin_panel(): void
    {
        $component = Volt::test('pages.auth.login')
            ->set('form.email', $this->admin->email)
            ->set('form.password', 'password')
            ->call('login');

        $component->assertHasNoErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Login sebagai Mitra Toko melalui satu pintu dialihkan langsung ke /toko.
     */
    public function test_store_owner_logs_in_via_unified_door_and_redirects_to_store_panel(): void
    {
        $component = Volt::test('pages.auth.login')
            ->set('form.email', $this->tokoUser->email)
            ->set('form.password', 'password')
            ->call('login');

        $component->assertHasNoErrors()
            ->assertRedirect('/toko');

        $this->assertAuthenticatedAs($this->tokoUser);
    }

    /**
     * Login sebagai Teknisi melalui satu pintu dialihkan langsung ke /teknisi/pesanan.
     */
    public function test_technician_logs_in_via_unified_door_and_redirects_to_technician_dashboard(): void
    {
        $component = Volt::test('pages.auth.login')
            ->set('form.email', $this->technician->email)
            ->set('form.password', 'password')
            ->call('login');

        $component->assertHasNoErrors()
            ->assertRedirect(route('technician.dashboard', absolute: false));

        $this->assertAuthenticatedAs($this->technician);
    }

    /**
     * Login sebagai Pelanggan melalui satu pintu dialihkan langsung ke /dashboard.
     */
    public function test_customer_logs_in_via_unified_door_and_redirects_to_dashboard(): void
    {
        $component = Volt::test('pages.auth.login')
            ->set('form.email', $this->customer->email)
            ->set('form.password', 'password')
            ->call('login');

        $component->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($this->customer);
    }

    /**
     * Pengguna yang sebelumnya ingin membuka halaman spesifik admin (/admin/commission-report)
     * setelah login satu pintu akan dialihkan tepat ke halaman tujuan awal (intended URL).
     */
    public function test_intended_admin_url_is_preserved_through_unified_login(): void
    {
        // 1. Kunjungi halaman admin yang butuh autentikasi
        $this->get('/admin/commission-report')->assertRedirect('/login');

        // 2. Login di satu pintu
        $component = Volt::test('pages.auth.login')
            ->set('form.email', $this->admin->email)
            ->set('form.password', 'password')
            ->call('login');

        // 3. Dialihkan ke halaman intended
        $component->assertRedirect(url('/admin/commission-report'));
    }
}
