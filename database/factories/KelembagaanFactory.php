<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelembagaan>
 */
class KelembagaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company(); // contoh: "BEM Teknik" atau "Himpunan Informatika"
        $jurusan = [
            'S1-Teknik Informatika',
            'S1-Teknik Elektro',
            'S1-Teknik Mesin',
            'S1-Teknik Arsitektur',
            'S1-Teknik Sipil',
            'S1-Teknik Kelautan',
            'S1-Teknik Rekayasa dan Infrastruktur Lingkungan',
            'D3-Teknik Mesin',
            'D3-Teknik Sipil',
            'D3-Teknik Elektronika',
            'D3-Teknik Arsitektur',
        ];
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'jurusan' => Arr::random($jurusan),
            'logo' => 'default/no_image.png',
            'is_active' => $this->faker->boolean(90), // 90% aktif
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
