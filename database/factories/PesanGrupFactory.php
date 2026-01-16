<?php

namespace Database\Factories;

use App\Models\Grup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PesanGrup>
 */
class PesanGrupFactory extends Factory
{
    public function definition(): array
    {
        // Ambil ID acak dari database agar tidak error Foreign Key
        // Jika tabel kosong, fallback ke factory pembuatnya (create)
        $grupId = Grup::inRandomOrder()->first()->id ?? Grup::factory();
        $userId = User::inRandomOrder()->first()->id ?? User::factory();

        return [
            'grup_id' => $grupId,
            'user_id' => $userId,
            'pesan' => $this->faker->realText(rand(20, 100)), // Kalimat random yang terlihat nyata
            'lampiran_url' => $this->faker->optional(0.1)->imageUrl(640, 480, 'business', true), // 10% peluang ada gambar
            'is_pinned' => $this->faker->boolean(5), // 5% peluang pesan dipin
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'), // Waktu acak sebulan terakhir
            'updated_at' => now(),
        ];
    }
}