<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Mengambil ID Admin atau User yang tersedia untuk relasi user_id
        $userIds = User::pluck('id')->toArray();

        // Daftar judul berita agar data lebih bervariasi dan relevan dengan ZHIB
        $daftarJudul = [
            'Komunitas IT Jogja Gelar Workshop Laravel',
            'Update Fitur Baru Dashboard Admin ZHIB',
            'Lomba UI/UX Se-DIY Resmi Dibuka Hari Ini',
            'Tips Mengelola Komunitas Digital bagi Pemula',
            'Daftar Pemenang Hackathon Regional Sleman',
            'Grup Chat ZHIB: Wadah Kolaborasi Baru di DIY',
            'Pentingnya Skor Kepercayaan dalam Komunitas',
            'Jadwal Event Teknologi di Yogyakarta Januari 2026',
            'Cara Klaim XP Setelah Mengikuti Kegiatan Lomba',
            'E-Sports DIY Cup Kembali Hadir Tahun Ini',
            'ZHIB Memberikan Penghargaan Komunitas Paling Aktif',
            'Laporan Pelanggaran Kini Lebih Mudah di Dashboard',
            'Panduan Pembayaran Event via Transfer Bank',
            'Mengenal Kategori Komunitas di Platform ZHIB',
            'Workshop Keamanan Siber untuk Mahasiswa UTY',
            'Inovasi Digital: Masa Depan Startup di Jogja',
            'Pertemuan Rutin Moderator Komunitas se-DIY',
            'Berita Terbaru: Peningkatan Kecepatan Server ZHIB',
            'Daftar Lomba Coding Gratis di Bulan Februari',
            'Pesan dari Admin: Mari Jaga Integritas Komunitas'
        ];

        foreach ($daftarJudul as $index => $judul) {
            $slug = Str::slug($judul);

            // Pastikan slug unik jika ada judul yang mirip
            if (Berita::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            Berita::create([
                'user_id'    => $faker->randomElement($userIds), //
                'judul'      => $judul,
                'slug'       => $slug,
                'konten'     => $faker->paragraphs(5, true), // Menghasilkan 5 paragraf teks berita
                'gambar_url' => 'berita/berita-' . ($index + 1) . '.jpg', // Path gambar
                'status'     => 'published', // Status default published
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}