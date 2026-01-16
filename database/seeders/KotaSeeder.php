<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Kota;
use Illuminate\Database\Seeder;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftar_kota = ['Sleman', 'Bantul', 'Yogyakarta', 'Kulon Progo', 'Gunung Kidul'];
        foreach ($daftar_kota as $k) {
            kota::create(['nama' => $k]);
        }
    }
}
