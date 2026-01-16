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
        Schema::create('komunitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kota_id')->nullable()->constrained('kota')->onDelete('set null');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('pembuat_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->text('icon_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komunitas');
    }
};
