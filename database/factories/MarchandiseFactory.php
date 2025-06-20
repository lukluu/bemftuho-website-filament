<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marchandise>
 */
class MarchandiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true); // contoh: "Kaos Polos", "Stiker Lucu"

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10000, 200000), // harga antara 10rb - 200rb
            'stock' => $this->faker->numberBetween(5, 100),
            'image_path' => 'default/no_image.png',
            'phone_number' => $this->faker->phoneNumber(),
            'is_active' => $this->faker->boolean(85), // 85% merchandise aktif
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
