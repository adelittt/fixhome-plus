<?php

namespace App\Livewire;

use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Models\ProductOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CustomerProductOrders extends Component
{
    public function confirmDelivered(int $orderId): void
    {
        $order = ProductOrder::query()
            ->where('customer_id', auth()->id())
            ->where('id', $orderId)
            ->firstOrFail();

        if ($order->status !== ProductOrderStatus::DIKIRIM) {
            session()->flash('error', 'Pesanan belum dalam status pengiriman.');
            return;
        }

        $order->update(['status' => ProductOrderStatus::SELESAI]);

        if ($order->shipment) {
            $order->shipment->update([
                'status' => ShipmentStatus::TERKIRIM,
                'delivered_at' => now(),
            ]);
        }

        session()->flash('success', "Pesanan #{$order->order_number} berhasil dikonfirmasi selesai. Terima kasih!");
    }

    public function render()
    {
        $orders = ProductOrder::query()
            ->with(['store', 'items.product', 'shipment', 'serviceOrder'])
            ->where('customer_id', auth()->id())
            ->latest('id')
            ->get();

        return view('livewire.customer-product-orders', [
            'orders' => $orders,
        ]);
    }
}
