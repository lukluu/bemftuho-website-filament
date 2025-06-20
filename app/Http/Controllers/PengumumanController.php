<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(3);
        $pengumuman = Pengumuman::latest()->first();
        $posts = Post::with('tags', 'category')->latest()->take(3)->get();
        $categories = Category::with('posts')->latest()->get();
        $events = Event::latest()->get();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.pengumuman', [
            'pengumumans' => $pengumumans,
            'pengumuman' => $pengumuman,
            'posts' => $posts,
            'categories' => $categories,
            'events' => $events,

            'kabinet' => $kabinet
        ]);
    }

    public function show(Pengumuman $pengumuman)
    {
        $posts = Post::with('tags', 'category')->latest()->take(3)->get();
        $pengumumans = Pengumuman::latest()->paginate(3);
        $categories = Category::with('posts')->latest()->get();
        $events = Event::latest()->get();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.pengumuman_show', [
            'pengumuman' => $pengumuman,
            'pengumumans' => $pengumumans,
            'posts' => $posts,
            'categories' => $categories,
            'events' => $events,

            'kabinet' => $kabinet
        ]);
    }
}
