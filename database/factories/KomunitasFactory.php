<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\Factory;

class KomunitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kota_id' => fake()->numberBetween(1, 5),
            
            'kategori_id' => fake()->numberBetween(1, 8),
            'pembuat_id' => 1, 

            'nama' => $this->faker->company() . ' Community',
            'deskripsi' => $this->faker->paragraph(),
            'icon_url' => 'image/komunitas/komunitas-default.jpg',
        ];
    }
}