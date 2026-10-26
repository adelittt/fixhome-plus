<?php

namespace App\Livewire;

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Models\ServiceOrder;
use App\Services\CommissionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TechnicianDashboard extends Component
{
    public string $activeTab = 'masuk'; // masuk, aktif, selesai, riwayat

    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isTeknisi()) {
            abort(403, 'Hanya teknisi yang berhak mengakses Dasbor Pekerjaan ini.');
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function acceptOrder(int $orderId): void
    {
        $order = ServiceOrder::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== ServiceOrderStatus::MENUNGGU_TEKNISI) {
            return;
        }

        $order->update(['status' => ServiceOrderStatus::DITERIMA]);
        session()->flash('success', "Pesanan {$order->order_number} berhasil diterima!");
    }

    public function rejectOrder(int $orderId, string $reason = 'Jadwal teknisi padat'): void
    {
        $order = ServiceOrder::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== ServiceOrderStatus::MENUNGGU_TEKNISI) {
            return;
        }

        $order->update([
            'status' => ServiceOrderStatus::DITOLAK,
            'cancellation_reason' => $reason,
        ]);

        session()->flash('info', "Pesanan {$order->order_number} telah ditolak.");
    }

    public function startOrder(int $orderId): void
    {
        $order = ServiceOrder::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== ServiceOrderStatus::DITERIMA) {
            return;
        }

        $order->update(['status' => ServiceOrderStatus::BERLANGSUNG]);
        session()->flash('success', "Pekerjaan pada pesanan {$order->order_number} telah dimulai.");
    }

    public function completeOrder(int $orderId, CommissionService $commissionService): void
    {
        $order = ServiceOrder::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== ServiceOrderStatus::BERLANGSUNG) {
            return;
        }

        $order->update([
            'status' => ServiceOrderStatus::SELESAI,
            'completed_at' => now(),
        ]);

        // Catat komisi jasa 5% di tabel terpisah service_commissions secara atomik
        $commissionService->recordServiceCommission($order);

        session()->flash('success', "Pesanan {$order->order_number} telah diselesaikan! Komisi jasa 5% telah dicatat.");
    }

    public function render()
    {
        $query = ServiceOrder::query()
            ->with(['customer', 'category', 'serviceCommission'])
            ->where('technician_id', auth()->id());

        $orders = match ($this->activeTab) {
            'masuk' => (clone $query)->where('status', ServiceOrderStatus::MENUNGGU_TEKNISI)->latest()->get(),
            'aktif' => (clone $query)->whereIn('status', [ServiceOrderStatus::DITERIMA, ServiceOrderStatus::BERLANGSUNG])->latest()->get(),
            'selesai' => (clone $query)->where('status', ServiceOrderStatus::SELESAI)->latest()->get(),
            'riwayat' => (clone $query)->whereIn('status', [ServiceOrderStatus::DITOLAK, ServiceOrderStatus::DIBATALKAN])->latest()->get(),
            default => collect(),
        };

        $counts = [
            'masuk' => ServiceOrder::where('technician_id', auth()->id())->where('status', ServiceOrderStatus::MENUNGGU_TEKNISI)->count(),
            'aktif' => ServiceOrder::where('technician_id', auth()->id())->whereIn('status', [ServiceOrderStatus::DITERIMA, ServiceOrderStatus::BERLANGSUNG])->count(),
            'selesai' => ServiceOrder::where('technician_id', auth()->id())->where('status', ServiceOrderStatus::SELESAI)->count(),
            'riwayat' => ServiceOrder::where('technician_id', auth()->id())->whereIn('status', [ServiceOrderStatus::DITOLAK, ServiceOrderStatus::DIBATALKAN])->count(),
        ];

        $totalEarning = \App\Models\ServiceCommission::where('technician_id', auth()->id())
            ->whereIn('status', ['tercatat', 'dicairkan'])
            ->sum('technician_earning');

        return view('livewire.technician-dashboard', [
            'orders' => $orders,
            'counts' => $counts,
            'totalEarning' => $totalEarning,
        ]);
    }
}
