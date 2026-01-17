<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BadgeUserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Mengambil ID Badge yang tersedia
        $badgeIds = Badge::pluck('id')->toArray();

        // Mengambil ID User dalam rentang 2 sampai 13
        // Pastikan user dengan ID tersebut sudah ada di database
        $userIds = User::whereBetween('id', [2, 13])->pluck('id')->toArray();

        if (empty($badgeIds) || empty($userIds)) {
            return;
        }

        foreach ($userIds as $userId) {
            // Setiap user dalam rentang tersebut akan mendapatkan 1-3 badge secara acak
            $randomBadges = (array) array_rand(array_flip($badgeIds), $faker->numberBetween(1, 3));

            foreach ($randomBadges as $badgeId) {
                DB::table('badge_user')->insert([
                    'user_id'   => $userId,
                    'badge_id'  => $badgeId,
                    'earned_at' => $faker->dateTimeBetween('-6 months', 'now'), // Sebelum sekarang
                ]);
            }
        }
    }
}