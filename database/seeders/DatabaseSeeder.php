<?php

namespace Database\Seeders;

use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Jabatan;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use App\Models\Kelembagaan;
use App\Models\Marchandise;
use Illuminate\Support\Str;
use App\Models\LinkAspirasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        \App\Models\Mahasiswa::factory()->count(5)->create();
        Pengumuman::factory()->count(5)->create();
        Marchandise::factory()->count(5)->create();
        Kelembagaan::factory()->count(5)->create();
        LinkAspirasi::create([
            'link' => 'https://example.com/aspirasi/' . Str::random(8),
            'hero' => 'default/no_image.png',
            'deskripsi' => fake()->sentence(10),
        ]);
        User::create([
            'name' => 'Admin',
            'username' => 'lukman',
            'email' => 'l@l.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
        Category::create([
            'name' => 'Berita Kampus',
            'image' => 'categories/berita-kampus.jpg',
        ]);
        Category::create([
            'name' => 'Kegiatan BEM',
            'image' => 'categories/kegiatan-bem.jpg',
        ]);
        Category::create([
            'name' => 'Opini',
            'image' => 'categories/opini.jpg',
        ]);
        Category::create([
            'name' => 'Keteknikan',
            'image' => 'categories/keteknikan.jpg',
        ]);
        Category::create([
            'name' => 'Kemahasiswaan',
            'image' => 'categories/kemahasiswaan.jpg',
        ]);
        Tag::create([
            'name' => 'seminar',

        ]);
        Tag::create([
            'name' => 'prestasi',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Ketua Umum',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Wakil Ketua',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Sekretaris Jenderal',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Bendahara Umum',
        ]);
        Kabinet::create([
            'nama_kabinet' => 'Sinergitas',
            'periode' => '2023/2024',
            'logo' => 'kabinet/logo.png',
            'visi' => 'Visi Kabinet 1',
            'misi' => 'Misi Kabinet 1',
            'tagline' => 'Tagline Kabinet 1',
        ]);
        Kabinet::create([
            'nama_kabinet' => 'Kabinet 2',
            'periode' => '2023/2024',
            'visi' => 'Visi Kabinet 2',
            'logo' => 'default/no_image.png',
            'misi' => 'Misi Kabinet 2',
            'tagline' => 'Tagline Kabinet 2',

        ]);
        $this->call([
            EventSeeder::class,
        ]);
        Post::factory()->count(15)->create();
    }
}
