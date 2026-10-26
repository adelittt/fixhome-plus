<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->onDelete('set null');
            $table->text('bio')->nullable();
            $table->unsignedBigInteger('base_rate')->default(50000); // Rupiah integer
            $table->string('verification_status')->default('menunggu'); // VerificationStatus enum
            $table->boolean('is_available')->default(true);
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::create('technician_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_profile_id')->constrained('technician_profiles')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price'); // Rupiah integer
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_services');
        Schema::dropIfExists('technician_profiles');
    }
};
