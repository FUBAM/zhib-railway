<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@mail.com';

        $user = User::firstWhere('email', $email);
        if ($user) {
            if ($user->role !== 'admin') {
                $user->role = 'admin';
                $user->save();
            }
            $this->command->info("Admin user exists: {$email}");
            return;
        }

        User::create([
            'nama' => 'admin',
            'email' => $email,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'xp_terkini' => 0,
            'level_terkini' => 1,
            'skor_kepercayaan' => 100,
            'terpercaya' => true,
            'foto_profil_url' => 'image/avatar/default.jpg',
        ]);

        $this->command->info("Created admin user: {$email} (password: admin123)");

        
        $user1 = [
            [
                'nama' => 'user1',
                'email' => 'user@satu.id',
                'password' => Hash::make('usersatu'),
                'role' => 'member',
                'xp_terkini' => 666,
                'level_terkini' => 6,
                'skor_kepercayaan' => 99,
                'terpercaya' => true,
                'foto_profil_url' => 'image/avatar/default.jpg',
            ],
        ];
                
        DB::table('users')->insertOrIgnore($user1);
                
        $user3 = [
            [
                'nama' => 'user3',
                'email' => 'user@stiga.id',
                'password' => Hash::make('usertiga'),
                'role' => 'member',
                'xp_terkini' => 999,
                'level_terkini' => 9,
                'skor_kepercayaan' => 99,
                'terpercaya' => true,
                'foto_profil_url' => 'image/avatar/default.jpg',
            ],
        ];
        DB::table('users')->insertOrIgnore($user3);
                
        User::factory(10)->create();
    }
}