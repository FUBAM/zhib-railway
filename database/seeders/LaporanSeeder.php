<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\User;
use Faker\Factory as Faker;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Mengambil semua ID User kecuali ID 1
        $userIds = User::where('id', '!=', 1)->pluck('id')->toArray();

        // Daftar alasan laporan yang bervariasi
        $daftarAlasan = [
            'Menggunakan foto profil yang tidak pantas.',
            'Melakukan spam di kolom komentar event.',
            'Memberikan informasi palsu pada profil.',
            'Menggunakan nama pengguna yang mengandung unsur SARA.',
            'Melakukan pelecehan verbal melalui pesan grup.',
            'Berpura-pura menjadi admin atau moderator.',
            'Mempromosikan konten ilegal atau perjudian.',
            'Akun terindikasi sebagai akun bot atau palsu.',
            'Melakukan kecurangan pada saat klaim XP event.',
            'Mengganggu kenyamanan anggota komunitas lainnya.'
        ];

        for ($i = 0; $i < 10; $i++) {
            // Pilih pelapor dan target secara acak dari list userIds
            $pelaporId = $faker->randomElement($userIds);
            
            // Pastikan pelapor tidak melaporkan dirinya sendiri
            $targetId = $faker->randomElement(array_diff($userIds, [$pelaporId]));

            Laporan::create([
                'pelapor_id'  => $pelaporId, //
                'tipe_target' => 'user',      //
                'target_id'   => $targetId,   //
                'alasan'      => $faker->randomElement($daftarAlasan), //
                'status'      => 'pending', //
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}