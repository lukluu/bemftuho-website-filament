<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengumuman>
 */
class PengumumanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4); // contoh: "Pendaftaran Lomba Karya Tulis"

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'user_id' => User::inRandomOrder()->first()->id,
            'content' => $this->faker->paragraphs(3, true), // gabung 3 paragraf jadi 1 teks
            'is_active' => $this->faker->boolean(90), // 90% aktif
            'image' => 'default/no_image.png',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
