<?php

namespace App\Http\Controllers\Page;

use App\Models\Event;
use App\Models\Kabinet;
use App\Models\Category;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KabinetMahasiswaJabatan;

class AboutController extends Controller
{
    public function index()
    {
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa.sosmedMhs.sosmed', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        $kabinets = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->get();
        return view(
            'page.about',
            [
                'kabinet' => $kabinet,
                'kabinets' => $kabinets
            ]
        );
    }

    public function show(Kabinet $kabinet)
    {
        $kabinets = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa.sosmedMhs.sosmed', 'kabinetMahasiswaJabatan.jabatan')->get();
        return view('page.kabinet', [
            'kabinet' => $kabinet,
            'kabinets' => $kabinets
        ]);
    }

    public function struktur()
    {
        $categories = Category::with('posts')->latest()->get();
        $pengumuman = Pengumuman::latest()->first();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        $kabinets = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->get();
        $event = Event::with('category')->latest()->get();
        return view(
            'page.struktur',
            [
                'kabinet' => $kabinet,
                'events' => $event,
                'categories' => $categories,
                'pengumuman' => $pengumuman,
                'kabinets' => $kabinets
            ]
        );
    }
}
