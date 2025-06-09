<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Jabatan;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use App\Models\Kelembagaan;
use App\Models\Marchandise;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();

        // dd($kabinet);
        $pengumuman = Pengumuman::all();
        $event = Event::take(3)->get();
        $marchandise = Marchandise::latest()->first();
        $posts = Post::with('tags', 'category')->latest()->first();
        $category = Category::take(4)->get();
        $kelembagaan = Kelembagaan::all();
        return view('welcome', [

            'kabinet' => $kabinet,
            'pengumumans' => $pengumuman,
            'events' => $event,
            'marchandises' => $marchandise,
            'posts' => $posts,
            'kelembagaans' => $kelembagaan,
            'categories' => $category
        ]);
    }
}
