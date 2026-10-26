<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->integer('revision_count')->default(0);
            $table->timestamps();
        });

        Schema::create('material_recommendation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_recommendation_id')->constrained('material_recommendations')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('material_name');
            $table->text('specification')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('pcs');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_recommendation_items');
        Schema::dropIfExists('material_recommendations');
    }
};
