<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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

        $jenisKelamin = ['Laki-laki', 'Perempuan'];

        return [
            'nama' => $this->faker->name($jenisKelamin[rand(0, 1)]),
            'nim' => $this->generateNIM(),
            'jurusan' => Arr::random($jurusan),
            'angkatan' => (string) $this->faker->numberBetween(2018, 2025),
            'gander' => Arr::random($jenisKelamin), // Perhatikan typo, seharusnya 'gender'
        ];
    }

    protected function generateNIM(): string
    {
        $tahun = $this->faker->numberBetween(18, 23); // Angkatan 2018-2023
        $prodi = $this->faker->numberBetween(100, 999); // Kode prodi acak
        $urutan = $this->faker->unique()->numberBetween(1000, 9999); // Nomor urut

        return "{$tahun}{$prodi}{$urutan}";
    }
}
