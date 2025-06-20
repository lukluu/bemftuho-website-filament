<?php

namespace Database\Factories;

use App\Models\Sosmed;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SosmedMahasiswa>
 */
class SosmedMahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mahasiswa_id' => Mahasiswa::inRandomOrder()->first()->id,
            'sosmed_id' => Sosmed::inRandomOrder()->first()->id,
            'link' => $this->faker->url(),
        ];
    }
}
