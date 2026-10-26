<?php

namespace App\Livewire;

use App\Enums\VerificationStatus;
use App\Models\ServiceCategory;
use App\Models\TechnicianProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class ServiceCatalog extends Component
{
    #[Url]
    public ?int $selectedCategory = null;

    #[Url]
    public string $search = '';

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function render()
    {
        $categories = ServiceCategory::where('is_active', true)->get();

        $technicians = TechnicianProfile::query()
            ->with(['user', 'category', 'services'])
            ->where('verification_status', VerificationStatus::DISETUJUI)
            ->where('is_available', true)
            ->when($this->selectedCategory, fn ($q) => $q->where('service_category_id', $this->selectedCategory))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'ilike', "%{$this->search}%"))
                        ->orWhere('address', 'ilike', "%{$this->search}%")
                        ->orWhere('bio', 'ilike', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        return view('livewire.service-catalog', [
            'categories' => $categories,
            'technicians' => $technicians,
        ]);
    }
}
