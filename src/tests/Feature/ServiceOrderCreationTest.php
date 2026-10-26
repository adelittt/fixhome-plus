<?php

namespace Tests\Feature;

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Livewire\ServiceBookingForm;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\TechnicianProfile;
use App\Models\User;
use App\Services\Contracts\RouteDistanceServiceInterface;
use App\Services\FakeRouteDistanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceOrderCreationTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected ServiceCategory $category;
    protected FakeRouteDistanceService $fakeRouter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeRouter = new FakeRouteDistanceService();
        $this->app->instance(RouteDistanceServiceInterface::class, $this->fakeRouter);

        $this->customer = User::factory()->create([
            'role' => UserRole::PELANGGAN,
            'name' => 'Bambang Pelanggan',
        ]);

        $this->technician = User::factory()->create([
            'role' => UserRole::TEKNISI,
            'name' => 'Joko Teknisi',
        ]);

        $this->category = ServiceCategory::create([
            'name' => 'Perbaikan Pipa Air',
            'slug' => 'perbaikan-pipa-air',
        ]);

        TechnicianProfile::create([
            'user_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'base_rate' => 75000,
            'verification_status' => VerificationStatus::DISETUJUI,
            'latitude' => -6.1700,
            'longitude' => 106.6400,
        ]);
    }

    /**
     * Pelanggan membuat pesanan jasa dengan jarak rute < 10 km:
     * distance_fee = Rp0, total_service_fee = Rp75.000.
     */
    public function test_customer_can_book_service_under_10km_without_distance_fee(): void
    {
        $this->fakeRouter->setDistanceKm(6.5);

        Livewire::actingAs($this->customer)
            ->test(ServiceBookingForm::class, ['technicianId' => $this->technician->id])
            ->set('job_description', 'Kran wastafel bocor air menetes terus menerus')
            ->set('scheduled_at', now()->addDay()->format('Y-m-d\TH:i'))
            ->set('address', 'Jl. Merdeka No. 25, Tangerang')
            ->set('latitude', -6.1800)
            ->set('longitude', 106.6500)
            ->call('submitBooking')
            ->assertHasNoErrors()
            ->assertRedirect();

        $order = ServiceOrder::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($this->customer->id, $order->customer_id);
        $this->assertEquals($this->technician->id, $order->technician_id);
        $this->assertEquals(ServiceOrderStatus::MENUNGGU_TEKNISI, $order->status);
        $this->assertEquals(75000, $order->base_service_fee);
        $this->assertEquals(0, $order->distance_fee);
        $this->assertEquals(75000, $order->total_service_fee);
    }

    /**
     * Pelanggan membuat pesanan jasa dengan jarak rute >= 10 km:
     * distance_fee = Rp10.000, total_service_fee = Rp75.000 + Rp10.000 = Rp85.000.
     */
    public function test_customer_can_book_service_over_10km_with_distance_fee(): void
    {
        $this->fakeRouter->setDistanceKm(14.8);

        Livewire::actingAs($this->customer)
            ->test(ServiceBookingForm::class, ['technicianId' => $this->technician->id])
            ->set('job_description', 'Instalasi pipa air baru untuk kamar mandi lantai dua')
            ->set('scheduled_at', now()->addDays(2)->format('Y-m-d\TH:i'))
            ->set('address', 'Jl. Daan Mogot KM 18, Jakarta Barat')
            ->set('latitude', -6.1600)
            ->set('longitude', 106.7100)
            ->call('submitBooking')
            ->assertHasNoErrors()
            ->assertRedirect();

        $order = ServiceOrder::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(10000, $order->distance_fee);
        $this->assertEquals(85000, $order->total_service_fee);
    }

    /**
     * Validasi pemesanan jasa: deskripsi wajib diisi dan minimal 5 karakter.
     */
    public function test_booking_validation_errors_when_fields_are_invalid(): void
    {
        Livewire::actingAs($this->customer)
            ->test(ServiceBookingForm::class, ['technicianId' => $this->technician->id])
            ->set('job_description', 'test') // kurang dari 5 karakter
            ->set('scheduled_at', '')
            ->set('address', '')
            ->call('submitBooking')
            ->assertHasErrors(['job_description', 'scheduled_at', 'address']);
    }
}
