<?php

namespace App\Livewire;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Models\MaterialRecommendation;
use App\Models\ServiceOrder;
use Livewire\Component;

class MaterialRecommendationViewer extends Component
{
    public ServiceOrder $order;
    public ?MaterialRecommendation $recommendation = null;

    protected $listeners = ['recommendation-updated' => 'loadRecommendation'];

    public function mount(ServiceOrder $order): void
    {
        $this->order = $order;
        $this->loadRecommendation();
    }

    public function loadRecommendation(): void
    {
        $this->order->refresh();
        $this->recommendation = MaterialRecommendation::query()
            ->with(['items.product'])
            ->where('service_order_id', $this->order->id)
            ->first();
    }

    /**
     * Memilih opsi Marketplace (PRD 4.2 & AB-006).
     * Tombol "Lihat Marketplace" dan "Lewati Marketplace" berstatus setara.
     * Memilih "Lewati Marketplace" tidak memblokir alur pesanan jasa.
     */
    public function chooseOption(string $choice): void
    {
        // Pilihan hanya dapat diubah selama pesanan jasa belum selesai (PK-17)
        if ($this->order->status === ServiceOrderStatus::SELESAI || $this->order->status === ServiceOrderStatus::DIBATALKAN) {
            session()->flash('choice_error', 'Status pesanan jasa sudah final dan tidak dapat diubah lagi.');
            return;
        }

        $choiceEnum = MarketplaceChoice::from($choice);
        $this->order->update(['marketplace_choice' => $choiceEnum]);

        if ($choiceEnum === MarketplaceChoice::LEWATI) {
            session()->flash('choice_success', 'Anda memilih untuk menyediakan material secara mandiri. Pesanan jasa tetap berjalan sesuai jadwal tanpa hambatan.');
        } else {
            session()->flash('choice_success', 'Pilihan Lihat Marketplace berhasil disimpan.');
        }

        $this->order->refresh();
    }

    public function render()
    {
        return view('livewire.material-recommendation-viewer');
    }
}
