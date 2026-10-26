<?php

namespace App\Livewire;

use App\Enums\ServiceOrderStatus;
use App\Models\MaterialRecommendation;
use App\Models\MaterialRecommendationItem;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MaterialRecommendationManager extends Component
{
    public ServiceOrder $order;
    public string $notes = '';
    public array $items = [];

    protected function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_name' => ['required', 'string', 'min:2', 'max:255'],
            'items.*.specification' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit' => ['required', 'string', 'max:50'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'items.required' => 'Minimal harus menyertakan satu butir material rekomendasi.',
            'items.min' => 'Minimal harus menyertakan satu butir material rekomendasi.',
            'items.*.material_name.required' => 'Nama material wajib diisi.',
            'items.*.quantity.required' => 'Jumlah material wajib diisi.',
            'items.*.quantity.min' => 'Jumlah material minimal 1.',
            'items.*.unit.required' => 'Satuan material wajib diisi.',
        ];
    }

    public function mount(ServiceOrder $order): void
    {
        $this->order = $order;
        $this->loadExistingRecommendation();
    }

    public function loadExistingRecommendation(): void
    {
        $recommendation = MaterialRecommendation::query()
            ->with('items')
            ->where('service_order_id', $this->order->id)
            ->first();

        if ($recommendation) {
            $this->notes = $recommendation->notes ?? '';
            $this->items = $recommendation->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'material_name' => $item->material_name,
                    'specification' => $item->specification ?? '',
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'notes' => $item->notes ?? '',
                ];
            })->toArray();
        } else {
            // Default 1 butir kosong
            $this->items = [
                [
                    'material_name' => '',
                    'specification' => '',
                    'quantity' => 1,
                    'unit' => 'pcs',
                    'notes' => '',
                ],
            ];
        }
    }

    public function addItem(): void
    {
        $this->items[] = [
            'material_name' => '',
            'specification' => '',
            'quantity' => 1,
            'unit' => 'pcs',
            'notes' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function saveRecommendation(): void
    {
        // Otorisasi: hanya teknisi bersangkutan yang dapat membuat rekomendasi (AB-007)
        if (auth()->id() !== $this->order->technician_id) {
            abort(403, 'Hanya teknisi yang ditugaskan yang dapat menyusun rekomendasi material.');
        }

        // Rekomendasi hanya dapat dibuat bila pesanan diterima atau sedang berlangsung
        if (! in_array($this->order->status, [ServiceOrderStatus::DITERIMA, ServiceOrderStatus::BERLANGSUNG])) {
            session()->flash('error', 'Rekomendasi material hanya dapat dibuat saat pesanan berstatus Diterima atau Sedang Berjalan.');
            return;
        }

        $this->validate();

        DB::transaction(function () {
            $recommendation = MaterialRecommendation::firstOrNew(['service_order_id' => $this->order->id]);
            $recommendation->notes = $this->notes;
            $recommendation->revision_count = $recommendation->exists ? ($recommendation->revision_count + 1) : 0;
            $recommendation->save();

            // Simpan butir-butir material
            $recommendation->items()->delete();
            foreach ($this->items as $item) {
                MaterialRecommendationItem::create([
                    'material_recommendation_id' => $recommendation->id,
                    'material_name' => trim($item['material_name']),
                    'specification' => trim($item['specification'] ?? ''),
                    'quantity' => (int) $item['quantity'],
                    'unit' => trim($item['unit'] ?? 'pcs'),
                    'notes' => trim($item['notes'] ?? ''),
                ]);
            }
        });

        session()->flash('recommendation_success', 'Rekomendasi material berhasil dikirim ke pelanggan!');
        $this->dispatch('recommendation-updated');
    }

    public function render()
    {
        return view('livewire.material-recommendation-manager');
    }
}
