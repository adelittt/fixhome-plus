<?php

namespace App\Livewire;

use App\Enums\ShipmentStatus;
use App\Models\ProductOrder;
use App\Services\Contracts\InstantCourierServiceInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class OrderShipmentTracker extends Component
{
    public ProductOrder $order;
    public ?array $trackingData = null;

    public function mount(int $orderId, InstantCourierServiceInterface $courierService): void
    {
        $this->order = ProductOrder::query()
            ->with(['store', 'customer', 'shipment', 'items.product'])
            ->findOrFail($orderId);

        // Otorisasi: Hanya pemesan, toko bersangkutan, atau admin yang boleh mengakses
        $userId = auth()->id();
        $isCustomer = $userId === $this->order->customer_id;
        $isStoreOwner = $this->order->store && $this->order->store->user_id === $userId;
        $isAdmin = auth()->user()->isAdmin();

        if (! $isCustomer && ! $isStoreOwner && ! $isAdmin) {
            abort(403, 'Anda tidak memiliki hak akses melihat pelacakan pengiriman pesanan ini.');
        }

        $this->refreshTracking($courierService);
    }

    public function refreshTracking(InstantCourierServiceInterface $courierService): void
    {
        $this->order->refresh();

        if ($this->order->shipment && $this->order->shipment->tracking_number) {
            $this->trackingData = $courierService->trackShipment($this->order->shipment->tracking_number);
        }
    }

    /**
     * Fitur simulasi progress kurir untuk keperluan demo & pengujian (AB-013, PK-11).
     */
    public function simulateStep(string $statusValue, InstantCourierServiceInterface $courierService): void
    {
        if (! $this->order->shipment) {
            session()->flash('error', 'Data pengiriman belum terbit.');
            return;
        }

        try {
            $statusEnum = ShipmentStatus::from($statusValue);
            $courierService->updateStatus($this->order->shipment, $statusEnum);

            $this->refreshTracking($courierService);
            session()->flash('simulation_success', "Status pengiriman kurir berhasil diubah ke: {$statusEnum->label()}");
        } catch (\Throwable $e) {
            session()->flash('simulation_error', "Gagal memproses simulasi: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order-shipment-tracker');
    }
}
