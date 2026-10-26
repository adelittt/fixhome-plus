<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->onDelete('set null');
            $table->text('job_description');
            $table->dateTime('scheduled_at');
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->unsignedBigInteger('base_service_fee'); // Rupiah integer
            $table->unsignedBigInteger('distance_fee')->default(0); // Rp10.000 jika distance_km >= 10.00
            $table->unsignedBigInteger('total_service_fee'); // base_service_fee + distance_fee
            $table->string('status')->default('menunggu_teknisi'); // ServiceOrderStatus enum
            $table->string('marketplace_choice')->default('belum_dipilih'); // MarketplaceChoice enum
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['technician_id', 'status']);
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['service_order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('service_orders');
    }
};
