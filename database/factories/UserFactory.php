<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            // Mengubah randomElement menjadi nilai tetap 'member'
            'role' => 'member',
            'xp_terkini' => fake()->numberBetween(0, 10000),
            'level_terkini' => fake()->numberBetween(1, 50),
            'skor_kepercayaan' => fake()->numberBetween(0, 100),
            'terpercaya' => fake()->boolean(20), // 20% peluang jadi true
            'foto_profil_url' => 'image/avatar/avatar-default.jpg',
            'bio' => fake()->sentence(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * State khusus untuk Admin.
     * Tetap dipertahankan jika sewaktu-waktu Anda butuh membuat admin secara spesifik.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}