<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komunitas;
use App\Models\Kota;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KomunitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Mengambil ID yang tersedia untuk relasi
        $kotaIds = Kota::pluck('id')->toArray();
        $kategoriIds = Kategori::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Daftar nama komunitas untuk variasi data yang lebih nyata
        $daftarNama = [
            'Web Developer DIY', 'Sleman Design Hub', 'Bantul Creative Force', 
            'Tech Enthusiast Jogja', 'Koding Bareng UTY', 'Cyber Security DIY',
            'AI Research Group', 'Data Science Yogyakarta', 'Startup Digital Sleman',
            'Mobile Dev Community', 'IOT Makers Jogja', 'Open Source DIY',
            'UI/UX Designers Bantul', 'Game Dev Yogyakarta', 'Cloud Computing ID',
            'Blockchain Hub Jogja', 'DevOps Indonesia DIY', 'Digital Marketing Sleman',
            'E-Commerce Enthusiast', 'Information System Alumni'
        ];

        for ($i = 0; $i < 20; $i++) {
            // Menggunakan nama dari daftar jika tersedia, jika tidak pakai Faker
            $namaKomunitas = $daftarNama[$i] ?? $faker->company;

            Komunitas::create([
                'kota_id'     => $faker->randomElement($kotaIds), //
                'kategori_id' => $faker->randomElement($kategoriIds), //
                'pembuat_id'  => $faker->randomElement($userIds), //
                'nama'        => $namaKomunitas, //
                'deskripsi'   => 'Komunitas ini berfokus pada pengembangan ' . $namaKomunitas . 
                                 '. Mari bergabung untuk belajar dan berkolaborasi bersama para ahli di bidangnya.', //
                'icon_url'    => 'image/komunitas/k' . ($i + 1) . '.png', //
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
