<?php

namespace App\Livewire;

use App\Enums\ProductOrderStatus;
use App\Enums\ServiceOrderStatus;
use App\Models\CartItem;
use App\Models\ProductOrder;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CustomerDashboard extends Component
{
    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            if ($user->isAdmin()) {
                return redirect('/admin');
            }
            if ($user->isMitraToko()) {
                return redirect('/toko');
            }
            if ($user->isTeknisi()) {
                return redirect(route('technician.dashboard'));
            }
        }
    }

    public function render()
    {
        $userId = auth()->id();

        // 1. Pesanan Jasa Terkini & Statistik
        $serviceOrdersQuery = ServiceOrder::where('customer_id', $userId)
            ->with(['technician', 'category']);

        $recentServiceOrders = (clone $serviceOrdersQuery)
            ->latest()
            ->take(3)
            ->get();

        $activeServiceCount = ServiceOrder::where('customer_id', $userId)
            ->whereIn('status', [
                ServiceOrderStatus::MENUNGGU_TEKNISI,
                ServiceOrderStatus::DITERIMA,
                ServiceOrderStatus::BERLANGSUNG,
            ])->count();

        $completedServiceCount = ServiceOrder::where('customer_id', $userId)
            ->where('status', ServiceOrderStatus::SELESAI)
            ->count();

        // 2. Pesanan Marketplace Terkini & Statistik
        $productOrdersQuery = ProductOrder::where('customer_id', $userId)
            ->with(['store', 'shipment', 'items']);

        $recentProductOrders = (clone $productOrdersQuery)
            ->latest()
            ->take(3)
            ->get();

        $activeProductCount = ProductOrder::where('customer_id', $userId)
            ->whereIn('status', [
                ProductOrderStatus::DIBAYAR,
                ProductOrderStatus::DIPROSES,
                ProductOrderStatus::SIAP_DIKIRIM,
                ProductOrderStatus::DIKIRIM,
            ])->count();

        // 3. Jumlah Item di Keranjang Belanja
        $cartCount = CartItem::where('customer_id', $userId)->sum('quantity');

        // 4. Kategori Populer Layanan Jasa
        $categories = ServiceCategory::where('is_active', true)->take(4)->get();

        return view('livewire.customer-dashboard', [
            'recentServiceOrders' => $recentServiceOrders,
            'recentProductOrders' => $recentProductOrders,
            'activeServiceCount' => $activeServiceCount,
            'completedServiceCount' => $completedServiceCount,
            'activeProductCount' => $activeProductCount,
            'cartCount' => $cartCount,
            'categories' => $categories,
        ]);
    }
}
