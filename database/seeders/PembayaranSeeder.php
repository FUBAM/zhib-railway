<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Events;
use App\Models\User;
use Faker\Factory as Faker;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil data referensi
        // Hanya ambil event yang berbayar untuk simulasi pembayaran
        $eventIds = Events::where('berbayar', true)->pluck('id')->toArray();
        $userIds = User::where('role', 'member')->pluck('id')->toArray();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        if (empty($eventIds) || empty($userIds)) {
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $status = $faker->randomElement(['pending', 'lunas', 'ditolak']);
            $eventId = $faker->randomElement($eventIds);
            $event = Events::find($eventId);

            Pembayaran::create([
                'user_id'           => $faker->randomElement($userIds),
                'events_id'         => $eventId,
                'jumlah_bayar'      => $event->harga,
                'bukti_url'         => 'image/bukti-bayar/bb' . ($i + 1) . '.jpg',
                'status'            => $status,
                'diverifikasi_oleh' => ($status !== 'pending') ? $faker->randomElement($adminIds) : null,
                'alasan_penolakan'  => ($status === 'ditolak') ? 'Bukti transfer tidak terbaca atau nominal tidak sesuai.' : null,
                'created_at'        => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at'        => now(),
            ]);
        }
    }
}