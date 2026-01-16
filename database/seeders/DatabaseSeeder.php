<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            BeritaSeeder::class,
            KategoriSeeder::class,
            KotaSeeder::class,
            KomunitasSeeder::class,
            EventsSeeder::class,
            PesertaSeeder::class,
            GrupSeeder::class,
            LaporanSeeder::class,
        ]);
    }
}