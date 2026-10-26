<?php

namespace App\Livewire;

use App\Enums\VerificationStatus;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServiceOrder;
use App\Models\Store;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MarketplaceCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public ?int $categoryId = null;

    #[Url(as: 'toko')]
    public ?int $storeId = null;

    #[Url(as: 'urutkan')]
    public string $sort = 'terbaru'; // terbaru, termurah, termahal

    #[Url(as: 'pesanan_jasa')]
    public ?int $serviceOrderId = null;

    public ?ServiceOrder $serviceOrder = null;

    public function mount(): void
    {
        if ($this->serviceOrderId) {
            $this->serviceOrder = ServiceOrder::query()
                ->with(['materialRecommendation.items'])
                ->find($this->serviceOrderId);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingStoreId(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->reset(['search', 'categoryId', 'storeId', 'sort']);
        $this->resetPage();
    }

    public function addToCart(int $productId, int $quantity = 1): void
    {
        if (! auth()->check()) {
            session()->flash('cart_error', 'Silakan masuk (login) terlebih dahulu untuk menambahkan produk ke keranjang belanja.');
            $this->redirect(route('login'));
            return;
        }

        $product = Product::query()
            ->where('is_active', true)
            ->whereHas('store', function ($q) {
                $q->where('verification_status', VerificationStatus::DISETUJUI)
                  ->where('is_active', true);
            })
            ->findOrFail($productId);

        if ($product->stock < $quantity) {
            session()->flash('cart_error', 'Maaf, stok produk tidak mencukupi untuk jumlah yang diminta.');
            return;
        }

        $cartItem = CartItem::where('customer_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        $newQty = $cartItem ? ($cartItem->quantity + $quantity) : $quantity;

        if ($newQty > $product->stock) {
            session()->flash('cart_error', "Jumlah di keranjang melebihi stok yang tersedia ({$product->stock} unit).");
            return;
        }

        CartItem::updateOrCreate(
            [
                'customer_id' => auth()->id(),
                'product_id' => $productId,
            ],
            [
                'quantity' => $newQty,
            ]
        );

        $this->dispatch('cart-updated');
        session()->flash('cart_success', "Berhasil menambahkan \"{$product->name}\" ke keranjang belanja!");
    }

    public function render()
    {
        $query = Product::query()
            ->with(['store', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereHas('store', function ($q) {
                $q->where('verification_status', VerificationStatus::DISETUJUI)
                  ->where('is_active', true);
            });

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('description', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->where('product_category_id', $this->categoryId);
        }

        if ($this->storeId) {
            $query->where('store_id', $this->storeId);
        }

        match ($this->sort) {
            'termurah' => $query->orderBy('price', 'asc'),
            'termahal' => $query->orderBy('price', 'desc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(12);

        $categories = ProductCategory::orderBy('name')->get();
        $stores = Store::where('verification_status', VerificationStatus::DISETUJUI)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.marketplace-catalog', [
            'products' => $products,
            'categories' => $categories,
            'stores' => $stores,
        ]);
    }
}
