<?php

namespace App\Livewire;

use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ServiceOrderDetail extends Component
{
    public ServiceOrder $order;

    public function mount(int $orderId): void
    {
        $this->order = ServiceOrder::query()
            ->with(['customer', 'technician.technicianProfile', 'category'])
            ->findOrFail($orderId);

        $userId = auth()->id();
        if (
            $userId !== $this->order->customer_id &&
            $userId !== $this->order->technician_id &&
            ! auth()->user()->isAdmin()
        ) {
            abort(403, 'Anda tidak memiliki hak akses melihat pesanan ini.');
        }
    }

    public function render()
    {
        return view('livewire.service-order-detail');
    }
}
