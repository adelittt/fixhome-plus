<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->string('payable_type'); // Morph (ServiceOrder / ProductOrder)
            $table->unsignedBigInteger('payable_id');
            $table->unsignedBigInteger('amount'); // Rupiah integer
            $table->string('payment_method')->default('simulasi_qris');
            $table->string('status')->default('menunggu'); // PaymentStatus enum
            $table->timestamp('paid_at')->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
            $table->index('status');
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_order_id')->constrained('product_orders')->onDelete('cascade');
            $table->string('provider_name')->default('simulasi_kurir_instan');
            $table->string('tracking_number')->nullable()->unique();
            $table->text('origin_address');
            $table->text('destination_address');
            $table->unsignedBigInteger('shipping_cost'); // Rupiah integer
            $table->decimal('estimated_hours', 5, 1)->default(2.0);
            $table->string('status')->default('belum_dibuat'); // ShipmentStatus enum
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['product_order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payments');
    }
};
