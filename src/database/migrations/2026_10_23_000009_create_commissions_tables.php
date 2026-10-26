<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Terpisah: Komisi Jasa Perbaikan Rumah (5% platform, 95% teknisi)
        Schema::create('service_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users')->onDelete('restrict');
            $table->unsignedBigInteger('gross_amount'); // Dasar komisi jasa (Rupiah integer)
            $table->decimal('platform_rate', 5, 4)->default(0.0500); // 5%
            $table->unsignedBigInteger('platform_commission'); // Nilai komisi platform (Rupiah integer)
            $table->unsignedBigInteger('technician_earning'); // Bagian teknisi (Rupiah integer = gross - commission)
            $table->string('status')->default('tercatat'); // tercatat, ditunda, dibatalkan, dicairkan
            $table->timestamps();

            $table->index(['technician_id', 'status']);
        });

        // 2. Tabel Terpisah: Komisi Marketplace Material (2% platform dari nilai produk murni, 98% toko)
        // Ongkos kirim DILARANG masuk ke dasar perhitungan komisi marketplace (AB-004)
        Schema::create('marketplace_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_order_id')->constrained('product_orders')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->unsignedBigInteger('gross_product_amount'); // Nilai murni produk, ongkir tidak dihitung (Rupiah integer)
            $table->decimal('platform_rate', 5, 4)->default(0.0200); // 2%
            $table->unsignedBigInteger('platform_commission'); // Nilai komisi platform (Rupiah integer)
            $table->unsignedBigInteger('store_earning'); // Bagian toko (Rupiah integer = gross - commission)
            $table->string('status')->default('tercatat'); // tercatat, ditunda, dibatalkan, dicairkan
            $table->timestamps();

            $table->index(['store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_commissions');
        Schema::dropIfExists('service_commissions');
    }
};
