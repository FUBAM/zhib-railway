<?php

namespace Database\Factories;

use App\Models\Events;
use App\Models\Kategori;
use App\Models\Komunitas;
use App\Models\Kota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventsFactory extends Factory
{
    protected $model = Events::class;

    public function definition(): array
    {
        // 1. Logic Berbayar atau Gratis (30% kemungkinan berbayar)
        $isBerbayar = $this->faker->boolean(30);
        $harga = $isBerbayar ? $this->faker->numberBetween(10, 500) * 1000 : 0; // Rp 10.000 - Rp 500.000

        // 2. Ambil ID relasi (Fallback ke null atau 1 jika tabel kosong untuk mencegah error)
        $kategori = Kategori::inRandomOrder()->first();
        $komunitas = Komunitas::inRandomOrder()->first();
        $kota = Kota::inRandomOrder()->first();
        $user = User::inRandomOrder()->first(); // User pengusul

        return [
            // Relasi
            'kategori_id' => $kategori ? $kategori->id : 1, // Pastikan tabel kategori di-seed duluan
            'komunitas_id' => $this->faker->boolean(70) ? ($komunitas ? $komunitas->id : null) : null, // 70% event terikat komunitas, 30% umum (null)
            'kota_id' => $this->faker->boolean(80) ? ($kota ? $kota->id : null) : null, // 80% event punya kota, 20% null
            'diusulkan_oleh' => $user ? $user->id : 1, // Default ke user ID 1

            // Data Event
            'type' => 'lomba',
            'judul' => $this->faker->sentence(4), // Contoh: "Lomba Coding Nasional 2024"
            'deskripsi' => $this->faker->paragraph(3),
            
            // Logic Harga
            'berbayar' => $isBerbayar,
            'harga' => $harga,
            
            'poster_url' => 'image/events/events-default.png', // Gambar dummy
            'status' => 'published',
            'start_date' => $this->faker->dateTimeBetween('now', '+3 months'),
        ];
    }
}