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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('komunitas_id')->nullable()->constrained('komunitas')->onDelete('cascade');
            $table->foreignId('kota_id')->nullable()->constrained('kota')->onDelete('set null');
            $table->foreignId('diusulkan_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['kegiatan', 'lomba']);
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->boolean('berbayar')->default(false);
            $table->decimal('harga', 10, 2)->default(0);
            $table->text('poster_url')->nullable();
            $table->enum('status', ['pending', 'published', 'finished'])->default('pending');
            $table->dateTime('start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
