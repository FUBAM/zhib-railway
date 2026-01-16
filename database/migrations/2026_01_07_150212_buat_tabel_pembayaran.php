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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('events_id')->constrained('events')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->text('bukti_url');
            $table->enum('status', ['pending', 'lunas', 'ditolak'])->default('pending');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->string('alasan_penolakan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};