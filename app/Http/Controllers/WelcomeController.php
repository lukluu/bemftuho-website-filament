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
use Filament\Forms\Get;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class WelcomeController extends Controller
{
    public function index()
    {
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa.sosmedMhs.sosmed', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        // dd($kabinet);
        $pengumuman = Pengumuman::latest()->get();
        $event = Event::take(3)->get();
        $marchandise = Marchandise::latest()->first();
        $posts = Post::with('tags', 'category')->latest()->first();

        $categories = Category::with('posts')->latest()->get();

        $kelembagaan = Kelembagaan::all();
        return view('welcome', [

            'kabinet' => $kabinet,
            'pengumumanList' => $pengumuman,
            'events' => $event,
            'marchandises' => $marchandise,
            'posts' => $posts,
            'categories' => $categories,
            'kelembagaans' => $kelembagaan,
        ]);
    }
}
