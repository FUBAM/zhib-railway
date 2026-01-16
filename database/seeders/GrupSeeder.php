<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komunitas;
use App\Models\Grup;

class GrupSeeder extends Seeder
{
    public function run()
    {
        // Mengambil semua komunitas yang sudah ada
        $komunitas = Komunitas::all();

        foreach ($komunitas as $kom) {
            // 1. Buat Grup Chat untuk setiap komunitas
            Grup::create([
                'komunitas_id' => $kom->id,
                'nama'         => 'Diskusi ' . $kom->nama,
                'type'         => 'chat',
                'is_read_only' => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // 2. Buat Grup Events/Pengumuman untuk setiap komunitas
            Grup::create([
                'komunitas_id' => $kom->id,
                'nama'         => 'Info Event ' . $kom->nama,
                'type'         => 'events',
                'is_read_only' => true, // Biasanya grup event bersifat satu arah (read-only)
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}