<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kota;
use App\Models\Kategori;
use App\Models\Badge;
use App\Models\User;
use App\Models\Berita;
use App\Models\Komunitas;
use App\Models\Events;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ISI DATA KOTA
        

        // // 3. ISI DATA BADGE (LENCANA)
        // badge::create([
        //     'nama' => 'Warga Teladan',
        //     'deskripsi' => 'Diberikan untuk user dengan skor kepercayaan tinggi',
        //     'xp_bonus' => 500,
        //     'image_url' => 'badges/teladan.png'
        // ]);

        // badge::create([
        //     'nama' => 'Pioneer',
        //     'deskripsi' => 'Diberikan untuk pembuat komunitas pertama',
        //     'xp_bonus' => 1000,
        //     'image_url' => 'badges/pioneer.png'
        // ]);

        // // 4. ISI DATA USER (ADMIN & MEMBER CONTOH)
        // // Dibuat agar kamu bisa langsung login untuk ngetes
        // $admin = User::create([
        //     'nama' => 'Admin Pusat',
        //     'email' => 'admin@mail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'admin',
        //     'xp_terkini' => 0,
        //     'level_terkini' => 1,
        //     'skor_kepercayaan' => 100,
        //     'terpercaya' => true,
        // ]);

        // 
        // 
        // 

    }
}