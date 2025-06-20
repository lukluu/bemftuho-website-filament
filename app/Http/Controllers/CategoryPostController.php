<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class CategoryPostController extends Controller
{
    public function show(Category $category)
    {
        $category->load('posts');

        $posts = $category->posts()->with('tags', 'category')->latest()->take(2)->get();
        $postss = $category->posts()->with('tags', 'category')->latest()->paginate(3);
        $categories = Category::with('posts')->latest()->get();
        $pengumuman = Pengumuman::latest()->first();
        $events = Event::latest()->get();

        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.category', [
            'posts' => $posts,
            'postss' => $postss,
            'category' => $category,
            'categories' => $categories,
            'pengumuman' => $pengumuman,
            'events' => $events,
            'kabinet' => $kabinet
        ]);
    }
}
