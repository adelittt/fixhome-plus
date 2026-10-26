<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technician_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->string('withdrawal_code')->unique();
            $table->integer('amount');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->text('notes')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, diproses, berhasil, ditolak
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_withdrawals');
    }
};
