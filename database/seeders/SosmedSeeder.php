<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SosmedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sosmed = [
            [
                'name' => 'Instagram',
            ],
            [
                'name' => 'Facebook',
            ],
            [
                'name' => 'Twitter',
            ],
            [
                'name' => 'Youtube',
            ],
            [
                'name' => 'Tiktok',
            ],
            [
                'name' => 'Linkedin',
            ],
        ];

        foreach ($sosmed as $s) {
            \App\Models\Sosmed::create($s);
        }
    }
}
