<?php

namespace App\Http\Controllers;

use App\Models\Kabinet;
use App\Models\LinkAspirasi;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class AspirasiController extends Controller
{
    public function index()
    {
        $aspirasis = LinkAspirasi::latest()->paginate(3);
        $aspirasiFirst = LinkAspirasi::latest()->first();
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.aspirasi', [
            'aspirasis' => $aspirasis,
            'aspirasiFirst' => $aspirasiFirst,
            'kabinet' => $kabinet
        ]);
    }
}
