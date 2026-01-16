<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use app\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Literasi & Penulisan',
                'icon_url' => 'image/icon/literasi.png', // Sesuaikan path icon Anda
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Seni & Desain',
                'icon_url' => 'image/icon/seni.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teknologi & Coding',
                'icon_url' => 'image/icon/teknologi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gaming & E-Sports',
                'icon_url' => 'image/icon/gaming.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kesehatan Mental',
                'icon_url' => 'image/icon/kesehatan.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gaya Hidup Solo',
                'icon_url' => 'image/icon/solo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Akademik & Sains',
                'icon_url' => 'image/icon/akademik.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pop Culture',
                'icon_url' => 'image/icon/popculture.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategori')->insert($kategori);
    }
}
