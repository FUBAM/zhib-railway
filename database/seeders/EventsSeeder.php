<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Events;
use App\Models\Kategori;
use App\Models\Komunitas;
use App\Models\Kota;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Pastikan User ID 1 (Admin/Pengusul) dan Kategori ID 1 ada di database Anda
        // Jika tidak, ganti angkanya dengan ID yang valid.
        $pengusulId = 1; 

        $events = [
            // 1. Event yang SUDAH SELESAI (Status: Finished, Tanggal: Lampau)
            [
                'id' => 21, // ID kita kunci jadi 21
                'kategori_id' => 3,
                'diusulkan_oleh' => $pengusulId,
                'type' => 'lomba',
                'judul' => 'Turnamen Mobile Legends Season 1',
                'deskripsi' => 'Turnamen yang sudah selesai dilaksanakan bulan lalu.',
                'berbayar' => true,
                'harga' => 50000,
                'poster_url' => 'image/events/events-default.png',
                'status' => 'finished',
                'start_date' => Carbon::now()->subMonth(), // 1 Bulan yang lalu
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 2. Event yang BELUM SELESAI (Status: Published, Tanggal: Masa Depan)
            [
                'id' => 22, // ID kita kunci jadi 22
                'kategori_id' => 8,
                'diusulkan_oleh' => $pengusulId,
                'type' => 'kegiatan',

                'judul' => 'Workshop Laravel 11 untuk Pemula',
                'deskripsi' => 'Workshop coding yang akan datang minggu depan.',
                'berbayar' => false,
                'harga' => 75000,
                'poster_url' => 'image/events/events-default.png',
                'status' => 'published',
                'start_date' => Carbon::now()->addWeek(), // 1 Minggu lagi
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 23,
                'kategori_id' => 1,
                'diusulkan_oleh' => $pengusulId,
                'type' => 'kegiatan',
                'judul' => 'Seminar Public Speaking (Menunggu Klaim)',
                'deskripsi' => 'Event ini sudah selesai, silakan tes upload bukti di sini.',
                'berbayar' => false,
                'harga' => 0,
                'poster_url' => 'image/events/events-default.png',
                'status' => 'finished', // Status Selesai
                'start_date' => Carbon::now()->subDays(3), // 3 Hari yang lalu
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Gunakan insertOrIgnore agar tidak error jika ID sudah ada
        DB::table('events')->insertOrIgnore($events);

        $faker = Faker::create('id_ID');

        // Mengambil ID referensi yang sudah ada di database
        $kategoriIds = Kategori::pluck('id')->toArray();
        $komunitasIds = Komunitas::pluck('id')->toArray();
        $kotaIds = Kota::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Daftar judul lomba agar data lebih bervariasi dan realistis
        $daftarJudul = [
            'UI/UX Design National Competition', 'Web Development Warrior 2026', 
            'Hackathon DIY Digital Talent', 'Lomba Keamanan Siber Regional',
            'Business Plan Challenge UTY', 'IOT Innovation Summit Lomba',
            'Mobile App Creative Contest', 'Data Science Hackfest Jogja',
            'Lomba Karya Tulis Ilmiah IT', 'Game Development DIY Cup',
            'Algoritma & Pemrograman Contest', 'Sleman Robotics Competition',
            'Database Design Championship', 'Cloud Architecture Challenge',
            'Digital Marketing Strategy Race', 'E-Sports Coding League',
            'Artificial Intelligence Expo Lomba', 'Tech Startup Pitching Day',
            'Lomba Jaringan & Infrastruktur', 'Software Engineering Excellence'
        ];

        for ($i = 0; $i < 20; $i++) {
            $isBerbayar = $faker->boolean(50); // 50% kemungkinan berbayar
            $harga = $isBerbayar ? $faker->randomElement([50000, 75000, 100000, 150000]) : 0;

            Events::create([
                'kategori_id'    => $faker->randomElement($kategoriIds),
                'komunitas_id'   => $faker->randomElement($komunitasIds),
                'kota_id'        => $faker->randomElement($kotaIds),
                'diusulkan_oleh' => $faker->randomElement($userIds),
                'type'           => 'lomba',
                'judul'          => $daftarJudul[$i],
                'deskripsi'      => $faker->paragraph(3),
                'berbayar'       => $isBerbayar,
                'harga'          => $harga,
                'poster_url'     => 'events/lomba-' . ($i + 1) . '.jpg',
                'status'         => 'published',
                'start_date'     => Carbon::now()->addDays($faker->numberBetween(7, 60)), // Dimulai 7-60 hari ke depan
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
