<?php

namespace App\Livewire;

use App\Enums\VerificationStatus;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductDetail extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount(int $id): void
    {
        $this->product = Product::query()
            ->with(['store', 'category'])
            ->where('is_active', true)
            ->whereHas('store', function ($q) {
                $q->where('verification_status', VerificationStatus::DISETUJUI)
                  ->where('is_active', true);
            })
            ->findOrFail($id);
    }

    public function increment(): void
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(bool $redirectToCart = false): void
    {
        if (! auth()->check()) {
            session()->flash('cart_error', 'Silakan login terlebih dahulu untuk berbelanja di marketplace.');
            $this->redirect(route('login'));
            return;
        }

        if ($this->quantity > $this->product->stock || $this->product->stock <= 0) {
            session()->flash('cart_error', 'Jumlah melebihi stok yang tersedia.');
            return;
        }

        $cartItem = CartItem::where('customer_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->first();

        $newQty = $cartItem ? ($cartItem->quantity + $this->quantity) : $this->quantity;

        if ($newQty > $this->product->stock) {
            session()->flash('cart_error', "Total di keranjang melebihi stok tersedia ({$this->product->stock} unit).");
            return;
        }

        CartItem::updateOrCreate(
            [
                'customer_id' => auth()->id(),
                'product_id' => $this->product->id,
            ],
            [
                'quantity' => $newQty,
            ]
        );

        $this->dispatch('cart-updated');

        if ($redirectToCart) {
            $this->redirect(route('cart.index'));
            return;
        }

        session()->flash('cart_success', "Berhasil menambahkan {$this->quantity} unit \"{$this->product->name}\" ke keranjang belanja!");
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
