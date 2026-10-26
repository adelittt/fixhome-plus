<?php

namespace App\Livewire;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ServiceBookingForm extends Component
{
    public int $technicianId;
    public User $technician;

    public string $job_description = '';
    public string $scheduled_at = '';
    public string $address = '';
    public float $latitude = -6.1753924;
    public float $longitude = 106.6431521;

    public float $distance_km = 0.0;
    public int $base_service_fee = 0;
    public int $distance_fee = 0;
    public int $total_service_fee = 0;

    protected function rules(): array
    {
        return [
            'job_description' => ['required', 'string', 'min:5', 'max:1000'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'address' => ['required', 'string', 'min:5', 'max:500'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ];
    }

    protected function messages(): array
    {
        return [
            'job_description.required' => 'Deskripsi pekerjaan perbaikan wajib diisi.',
            'job_description.min' => 'Deskripsi pekerjaan minimal 5 karakter.',
            'scheduled_at.required' => 'Jadwal kunjungan teknisi wajib ditentukan.',
            'scheduled_at.after' => 'Jadwal kunjungan harus di waktu mendatang.',
            'address.required' => 'Alamat lengkap pekerjaan wajib diisi.',
        ];
    }

    public function mount(int $technicianId, PricingService $pricingService): void
    {
        // Mendukung resolusi teknisi baik via users.id maupun technician_profiles.id
        $this->technician = User::query()
            ->where('role', UserRole::TEKNISI)
            ->where(function ($query) use ($technicianId) {
                $query->where('id', $technicianId)
                    ->orWhereHas('technicianProfile', fn ($q) => $q->where('id', $technicianId));
            })
            ->with(['technicianProfile.category', 'technicianProfile.services'])
            ->firstOrFail();

        $this->technicianId = $this->technician->id;

        $profile = $this->technician->technicianProfile;
        $this->base_service_fee = $profile?->base_rate ?? 50000;

        // Default jadwal besok jam 09:00
        $this->scheduled_at = now()->addDay()->setTime(9, 0)->format('Y-m-d\TH:i');

        // Alamat default pelanggan jika ada
        $this->address = 'Jl. Veteran No. 1, Babakan, Kota Tangerang';

        $this->calculatePricing($pricingService);
    }

    public function updateCoordinates(float $lat, float $lng): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->calculatePricing(app(PricingService::class));
    }

    public function calculatePricing(PricingService $pricingService): void
    {
        $profile = $this->technician->technicianProfile;
        $techLat = $profile?->latitude ?? $this->latitude;
        $techLng = $profile?->longitude ?? $this->longitude;

        $calc = $pricingService->calculateRouteAndDistanceFee(
            $techLat,
            $techLng,
            $this->latitude,
            $this->longitude
        );

        $this->distance_km = $calc['distance_km'];
        $this->distance_fee = $calc['distance_fee'];
        $this->total_service_fee = $this->base_service_fee + $this->distance_fee;
    }

    public function submitBooking()
    {
        $this->validate();

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $order = ServiceOrder::create([
            'order_number' => 'SRV-' . strtoupper(Str::random(8)),
            'customer_id' => auth()->id(),
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->technician->technicianProfile?->service_category_id,
            'job_description' => $this->job_description,
            'scheduled_at' => $this->scheduled_at,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance_km' => $this->distance_km,
            'base_service_fee' => $this->base_service_fee,
            'distance_fee' => $this->distance_fee,
            'total_service_fee' => $this->total_service_fee,
            'status' => ServiceOrderStatus::MENUNGGU_TEKNISI,
            'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
        ]);

        session()->flash('success', 'Pesanan jasa berhasil dibuat! Menunggu konfirmasi dari teknisi.');

        return redirect()->route('service.order.detail', $order->id);
    }

    public function render()
    {
        return view('livewire.service-booking-form');
    }
}
