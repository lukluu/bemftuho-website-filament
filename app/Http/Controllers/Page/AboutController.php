<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Kabinet;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view(
            'page.about',
            [
                'kabinet' => $kabinet
            ]
        );
    }
}
