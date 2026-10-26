<?php

namespace App\Livewire;

use App\Models\ServiceOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CustomerOrderList extends Component
{
    public function render()
    {
        $orders = ServiceOrder::query()
            ->with(['technician', 'category'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.customer-order-list', [
            'orders' => $orders,
        ]);
    }
}
