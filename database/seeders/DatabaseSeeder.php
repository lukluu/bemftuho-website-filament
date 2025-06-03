<?php

namespace Database\Seeders;

use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Category;
use App\Models\Kabinet;
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
        \App\Models\Mahasiswa::factory()->count(15)->create();
        User::create([
            'name' => 'Admin',
            'username' => 'lukman',
            'email' => 'l@l.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
        Category::create([
            'name' => 'Pengumuman',
        ]);
        Category::create([
            'name' => 'Kegiatan',
        ]);
        Tag::create([
            'name' => 'seminar',
        ]);
        Tag::create([
            'name' => 'info',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Ketua',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Wakil Ketua',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Sekretaris Jenderal',
        ]);
        Kabinet::create([
            'nama_kabinet' => 'Kabinet 1',
            'periode' => '2023/2024',
            'visi' => 'Visi Kabinet 1',
            'misi' => 'Misi Kabinet 1',
            'tagline' => 'Tagline Kabinet 1',
        ]);
        Kabinet::create([
            'nama_kabinet' => 'Kabinet 2',
            'periode' => '2023/2024',
            'visi' => 'Visi Kabinet 2',
            'misi' => 'Misi Kabinet 2',
            'tagline' => 'Tagline Kabinet 2',

        ]);
    }
}
