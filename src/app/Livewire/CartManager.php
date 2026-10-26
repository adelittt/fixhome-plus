<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\ServiceOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class CartManager extends Component
{
    #[Url(as: 'pesanan_jasa')]
    public ?int $serviceOrderId = null;

    public ?ServiceOrder $serviceOrder = null;

    // Default estimasi tarif pengiriman per mitra toko (Rupiah integer)
    public const DEFAULT_STORE_SHIPPING_FEE = 15000;

    protected $listeners = ['cart-updated' => '$refresh'];

    public function mount(): void
    {
        if ($this->serviceOrderId) {
            $this->serviceOrder = ServiceOrder::find($this->serviceOrderId);
        }
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $item = CartItem::where('customer_id', auth()->id())
            ->with('product')
            ->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $item->delete();
            $this->dispatch('cart-updated');
            session()->flash('cart_success', 'Item berhasil dihapus dari keranjang.');
            return;
        }

        if ($quantity > $item->product->stock) {
            session()->flash('cart_error', "Jumlah melebihi stok yang tersedia ({$item->product->stock} unit).");
            return;
        }

        $item->update(['quantity' => $quantity]);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId): void
    {
        CartItem::where('customer_id', auth()->id())
            ->where('id', $cartItemId)
            ->delete();

        $this->dispatch('cart-updated');
        session()->flash('cart_success', 'Item berhasil dihapus dari keranjang.');
    }

    public function proceedToCheckout()
    {
        $hasItems = CartItem::where('customer_id', auth()->id())->exists();
        if (! $hasItems) {
            session()->flash('cart_error', 'Keranjang belanja Anda masih kosong.');
            return;
        }

        $params = [];
        if ($this->serviceOrderId) {
            $params['pesanan_jasa'] = $this->serviceOrderId;
        }

        return redirect()->route('checkout.index', $params);
    }

    public function render()
    {
        $cartItems = CartItem::query()
            ->with(['product.store', 'product.category'])
            ->where('customer_id', auth()->id())
            ->get();

        // Mengelompokkan item berdasarkan Toko Mitra (AB-001 & AB-010)
        $storesGroup = $cartItems->groupBy(function ($item) {
            return $item->product->store_id;
        })->map(function ($items, $storeId) {
            $firstItem = $items->first();
            $store = $firstItem->product->store;

            $productSubtotal = $items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $shippingFee = self::DEFAULT_STORE_SHIPPING_FEE;
            $storeTotal = $productSubtotal + $shippingFee;

            return [
                'store' => $store,
                'items' => $items,
                'product_subtotal' => $productSubtotal,
                'shipping_fee' => $shippingFee,
                'store_total' => $storeTotal,
            ];
        });

        $totalProductAmount = $storesGroup->sum('product_subtotal');
        $totalShippingFee = $storesGroup->sum('shipping_fee');
        $grandTotal = $totalProductAmount + $totalShippingFee;

        return view('livewire.cart-manager', [
            'storesGroup' => $storesGroup,
            'totalProductAmount' => $totalProductAmount,
            'totalShippingFee' => $totalShippingFee,
            'grandTotal' => $grandTotal,
            'itemCount' => $cartItems->sum('quantity'),
        ]);
    }
}
