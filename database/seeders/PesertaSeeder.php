<?php

namespace Database\Seeders;

use App\Models\PesertaKegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan User dengan ID 12 SUDAH ADA di tabel users
        $userId = 12;

        $dataPeserta = [
            // 1. Peserta di Event 21 (Yang sudah selesai)
            // Asumsi: Karena event selesai, statusnya mungkin sudah 'hadir'
            [
                'user_id' => $userId,
                'events_id' => 21,
                'status' => 'hadir', 
                'bukti_url' => 'image/bukti/dummy.jpg',
                'review_text' => 'Eventnya seru banget!',
                'created_at' => now()->subMonth(),
                'updated_at' => now()->subMonth(),
            ],
            // 2. Peserta di Event 22 (Yang akan datang)
            // Asumsi: Event belum mulai, jadi status masih NULL
            [
                'user_id' => $userId,
                'events_id' => 22,
                'status' => null, 
                'bukti_url' => null,
                'review_text' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => $userId,
                'events_id' => 23, // ID Event yang selesai
                'status' => null, // PENTING: Null agar tombol klaim muncul
                'bukti_url' => null,
                'review_text' => null,
                'created_at' => now()->subDays(5), // Daftar sebelum event mulai
                'updated_at' => now()->subDays(5),
            ],
        ];

        DB::table('peserta_kegiatan')->insertOrIgnore($dataPeserta);
    }
}