<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::with('category')->latest()->paginate(3);
        $eventFirst = Event::with('category')->latest()->first();
        $categories = Category::with('posts')->latest()->get();
        $pengumuman = Pengumuman::latest()->first();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        $kabinets = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->get();
        $berita = Post::with('tags', 'category')->latest()->get();
        return view('page.event', [
            'events' => $event,
            'eventFirst' => $eventFirst,
            'categories' => $categories,
            'pengumuman' => $pengumuman,
            'kabinets' => $kabinets,
            'beritas' => $berita,
            'kabinet' => $kabinet
        ]);
    }

    public function show(Event $event)
    {
        $events = Event::with('category', 'kabinet')->latest()->paginate(3);
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.event_show', [
            'event' => $event,
            'events' => $events,
            'kabinet' => $kabinet
        ]);
    }
}
