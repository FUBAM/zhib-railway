<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $judul = $this->faker->sentence(6);

        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'konten' => $this->faker->paragraphs(3, true),
            'gambar_url' => 'image/berita/berita-default.jpg',
            'user_id' => 1,
            'status' => 'published',
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}