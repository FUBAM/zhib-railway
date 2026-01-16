<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Events;
use App\Models\PesanGrup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laporan>
 */
class LaporanFactory extends Factory
{
    public function definition(): array
    {
        // 1. Pilih Pelapor secara acak
        $pelapor = User::inRandomOrder()->first() ?? User::factory()->create();

        // 2. Tentukan Tipe Target secara acak
        $tipeTarget = $this->faker->randomElement(['events', 'pesan', 'user']);
        $targetId = null;

        // 3. Logika pengambilan target_id berdasarkan tipe
        switch ($tipeTarget) {
            case 'events':
                // Ambil ID event acak, atau buat baru jika kosong
                $event = Events::inRandomOrder()->first() ?? Events::factory()->create();
                $targetId = $event->id;
                break;

            case 'pesan':
                // Ambil ID pesan grup acak
                $pesan = PesanGrup::inRandomOrder()->first() ?? PesanGrup::factory()->create();
                $targetId = $pesan->id;
                break;

            case 'user':
                // Ambil ID user acak (selain pelapor agar tidak lapor diri sendiri)
                $targetUser = User::where('id', '!=', $pelapor->id)->inRandomOrder()->first() ?? User::factory()->create();
                $targetId = $targetUser->id;
                break;
        }

        return [
            'pelapor_id'  => $pelapor->id,
            'tipe_target' => $tipeTarget,
            'target_id'   => $targetId,
            'alasan'      => $this->faker->sentence(rand(3, 8)), // Alasan dummy
            'status'      => $this->faker->randomElement(['pending', 'resolved']),
            'created_at'  => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at'  => now(),
        ];
    }
}