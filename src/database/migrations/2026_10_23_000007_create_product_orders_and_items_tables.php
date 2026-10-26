<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('service_order_id')->nullable()->constrained('service_orders')->onDelete('set null');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->unsignedBigInteger('total_product_amount'); // Nilai produk murni (Rupiah integer)
            $table->unsignedBigInteger('shipping_fee')->default(0); // Ongkos kirim toko (Rupiah integer)
            $table->unsignedBigInteger('grand_total'); // total_product_amount + shipping_fee
            $table->string('status')->default('menunggu_pembayaran'); // ProductOrderStatus enum
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'status']);
            $table->index(['customer_id', 'status']);
        });

        Schema::create('product_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_order_id')->constrained('product_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('product_name');
            $table->unsignedBigInteger('price'); // Harga saat checkout (terkunci)
            $table->integer('quantity');
            $table->unsignedBigInteger('subtotal'); // price * quantity
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_order_items');
        Schema::dropIfExists('product_orders');
    }
};
