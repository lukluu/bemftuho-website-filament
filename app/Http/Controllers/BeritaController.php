<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $posts = Post::with('tags', 'category')->latest()->take(3)->get();
        $postss = Post::with('tags', 'category')->latest()->paginate(3);
        $categories = Category::with('posts')->latest()->get();
        $pengumuman = Pengumuman::latest()->first();
        $events = Event::latest()->get();

        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.berita', [
            'posts' => $posts,
            'postss' => $postss,
            'categories' => $categories,
            'pengumuman' => $pengumuman,
            'events' => $events,
            'kabinet' => $kabinet
        ]);
    }
    public function show(Category $category, Post $post)
    {
        // Validasi: pastikan post memang milik kategori yang sesuai
        if ($post->category_id !== $category->id) {
            abort(404);
        }
        $posts = Post::with('tags', 'category')->latest()->take(3)->get();
        $postss = Post::with('tags', 'category')->latest()->paginate(3);
        $categories = Category::with('posts')->latest()->get();
        $pengumuman = Pengumuman::latest()->first();
        $events = Event::latest()->get();

        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('berita.show', [
            'post' => $post->load('tags', 'category', 'user'),
            'posts' => $posts,
            'postss' => $postss,
            'categories' => $categories,
            'pengumuman' => $pengumuman,
            'events' => $events,
            'kabinet' => $kabinet,
            'category' => $category
        ]);
    }
}
