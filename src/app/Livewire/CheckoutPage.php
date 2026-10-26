<?php

namespace App\Livewire;

use App\Enums\PaymentStatus;
use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\ServiceOrder;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class CheckoutPage extends Component
{
    #[Url(as: 'pesanan_jasa')]
    public ?int $serviceOrderId = null;

    public ?ServiceOrder $serviceOrder = null;

    public string $recipientName = '';
    public string $recipientPhone = '';
    public string $deliveryAddress = '';
    public string $notes = '';
    public string $paymentMethod = 'simulasi_qris';

    public const STORE_SHIPPING_FEE = 15000;

    protected function rules(): array
    {
        return [
            'recipientName' => ['required', 'string', 'min:3', 'max:255'],
            'recipientPhone' => ['required', 'string', 'min:8', 'max:20'],
            'deliveryAddress' => ['required', 'string', 'min:10', 'max:500'],
            'notes' => ['nullable', 'string', 'max:500'],
            'paymentMethod' => ['required', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'recipientName.required' => 'Nama penerima wajib diisi.',
            'recipientPhone.required' => 'Nomor telepon penerima wajib diisi.',
            'deliveryAddress.required' => 'Alamat pengiriman material wajib diisi.',
            'deliveryAddress.min' => 'Alamat pengiriman minimal 10 karakter.',
        ];
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->recipientName = $user->name;
        $this->recipientPhone = $user->phone ?? '';

        if ($this->serviceOrderId) {
            $this->serviceOrder = ServiceOrder::find($this->serviceOrderId);
            if ($this->serviceOrder) {
                $this->deliveryAddress = $this->serviceOrder->address;
            }
        }
    }

    /**
     * Memproses checkout multi-toko (AB-010).
     * Memecah pesanan berdasarkan toko mitra, memotong stok, dan menyiapkan rekaman pengiriman & pembayaran.
     */
    public function placeOrder(bool $autoPay = true)
    {
        $this->validate();

        $cartItems = CartItem::query()
            ->with(['product.store'])
            ->where('customer_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            session()->flash('checkout_error', 'Keranjang belanja Anda kosong.');
            return redirect()->route('cart.index');
        }

        // Validasi ketersediaan stok
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->stock) {
                session()->flash('checkout_error', "Stok untuk produk \"{$cartItem->product->name}\" tidak mencukupi (tersedia: {$cartItem->product->stock}). Silakan sesuaikan jumlah di keranjang.");
                return redirect()->route('cart.index');
            }
        }

        $createdOrderIds = [];

        DB::transaction(function () use ($cartItems, $autoPay, &$createdOrderIds) {
            $groupedByStore = $cartItems->groupBy(function ($item) {
                return $item->product->store_id;
            });

            foreach ($groupedByStore as $storeId => $items) {
                $firstItem = $items->first();
                $store = $firstItem->product->store;

                $productSubtotal = $items->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                });

                $shippingFee = self::STORE_SHIPPING_FEE;
                $grandTotal = $productSubtotal + $shippingFee;

                $orderNumber = 'ORD-' . date('Ymd') . '-ST' . $storeId . '-' . strtoupper(Str::random(4));

                $initialStatus = $autoPay ? ProductOrderStatus::DIBAYAR : ProductOrderStatus::MENUNGGU_PEMBAYARAN;

                // 1. Buat pesanan per toko (AB-010)
                $productOrder = ProductOrder::create([
                    'order_number' => $orderNumber,
                    'service_order_id' => $this->serviceOrderId,
                    'customer_id' => auth()->id(),
                    'store_id' => $storeId,
                    'total_product_amount' => $productSubtotal,
                    'shipping_fee' => $shippingFee,
                    'grand_total' => $grandTotal,
                    'status' => $initialStatus,
                ]);

                $createdOrderIds[] = $productOrder->id;

                // 2. Buat butir-butir pesanan dan kurangi stok
                foreach ($items as $item) {
                    ProductOrderItem::create([
                        'product_order_id' => $productOrder->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->quantity * $item->product->price,
                    ]);

                    // Kurangi stok produk secara atomik
                    $item->product->decrement('stock', $item->quantity);
                }

                // 3. Buat rekaman pengiriman awal
                Shipment::create([
                    'product_order_id' => $productOrder->id,
                    'provider_name' => 'simulasi_kurir_instan',
                    'tracking_number' => 'TRK-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'origin_address' => $store->address ?: 'Gudang Toko ' . $store->name,
                    'destination_address' => $this->deliveryAddress . ' (Penerima: ' . $this->recipientName . ', Telp: ' . $this->recipientPhone . ')',
                    'shipping_cost' => $shippingFee,
                    'estimated_hours' => 2.0,
                    'status' => $autoPay ? ShipmentStatus::DIMINTA : ShipmentStatus::BELUM_DIBUAT,
                ]);

                // 4. Buat rekaman pembayaran (Payment)
                Payment::create([
                    'payment_number' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'payable_type' => ProductOrder::class,
                    'payable_id' => $productOrder->id,
                    'amount' => $grandTotal,
                    'payment_method' => $this->paymentMethod,
                    'status' => $autoPay ? PaymentStatus::BERHASIL : PaymentStatus::MENUNGGU,
                    'paid_at' => $autoPay ? now() : null,
                ]);
            }

            // 5. Bersihkan keranjang belanja
            CartItem::where('customer_id', auth()->id())->delete();
        });

        $this->dispatch('cart-updated');

        $orderCount = count($createdOrderIds);
        session()->flash('order_success', "Pembayaran berhasil dikonfirmasi! Telah dibuat {$orderCount} pesanan material terpisah ke masing-masing mitra toko.");

        return redirect()->route('customer.product-orders');
    }

    public function render()
    {
        $cartItems = CartItem::query()
            ->with(['product.store'])
            ->where('customer_id', auth()->id())
            ->get();

        $grouped = $cartItems->groupBy(function ($item) {
            return $item->product->store_id;
        })->map(function ($items) {
            $productSubtotal = $items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
            $shippingFee = self::STORE_SHIPPING_FEE;
            return [
                'store' => $items->first()->product->store,
                'items' => $items,
                'product_subtotal' => $productSubtotal,
                'shipping_fee' => $shippingFee,
                'total' => $productSubtotal + $shippingFee,
            ];
        });

        $totalProduct = $grouped->sum('product_subtotal');
        $totalShipping = $grouped->sum('shipping_fee');
        $grandTotal = $totalProduct + $totalShipping;

        return view('livewire.checkout-page', [
            'grouped' => $grouped,
            'totalProduct' => $totalProduct,
            'totalShipping' => $totalShipping,
            'grandTotal' => $grandTotal,
        ]);
    }
}
