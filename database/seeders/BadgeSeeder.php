<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\Komunitas;
use Faker\Factory as Faker;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Daftar 10 Event Baru untuk Deskripsi Badge Juara
        $eventBaru = [
            'Olimpiade Jaringan Nasional', 'Maraton Koding 24 Jam', 
            'Turnamen Keamanan Siber Mahasiswa', 'Sayembara Desain Poster Digital',
            'Kompetisi Robotika Industri', 'Lomba Analisis Data Bisnis',
            'Festival Film Pendek Teknologi', 'Kontes Arsitektur Perangkat Lunak',
            'Tantangan Inovasi IoT Smart City', 'Master Startup Pitching DIY'
        ];

        // 2. Loop untuk Badge Juara 1, 2, dan 3 (Masing-masing 10 variasi deskripsi)
        $tipeJuara = ['Juara 1', 'Juara 2', 'Juara 3'];

        foreach ($tipeJuara as $namaJuara) {
            foreach ($eventBaru as $event) {
                Badge::create([
                    'nama'      => $namaJuara,
                    'image_url' => 'image/badges/winner_' . $faker->numberBetween(1, 3) . '.png',
                    'deskripsi' => "Diberikan kepada pemenang $namaJuara dalam ajang $event.",
                    'xp_bonus'  => $faker->numberBetween(5, 55),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Buat Badge Moderator berdasarkan Nama Komunitas yang ada
        $komunitas = Komunitas::all();

        foreach ($komunitas as $kom) {
            Badge::create([
                'nama'      => 'Moderator',
                'image_url' => 'image/badges/moderator.png',
                'deskripsi' => "Lencana resmi bagi pengelola dan moderator komunitas " . $kom->nama . ".",
                'xp_bonus'  => $faker->numberBetween(5, 55),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}