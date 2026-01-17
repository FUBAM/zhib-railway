<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'users',
            'password_reset_tokens',
            'sessions',
            'kategori',
            'kota',
            'komunitas',
            'anggota_komunitas',
            'events',
            'peserta_kegiatan',
            'grup',
            'pesan_grup',
            'laporan',
            'badge',
            'badge_user',
            'pembayaran',
            'pemberitahuan',
            'berita',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
