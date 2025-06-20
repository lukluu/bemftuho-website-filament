<?php

namespace App\Http\Controllers;

use App\Models\Kabinet;
use App\Models\Marchandise;
use Illuminate\Http\Request;

class MarchandiseController extends Controller
{
    public function index()
    {
        $marc = Marchandise::latest()->paginate(3);
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.marchandise', [
            'marchandises' => $marc,
            'kabinet' => $kabinet
        ]);
    }
    public function show(Marchandise $marchandise)
    {
        $kabinet = Kabinet::with('kabinetMahasiswaJabatan.mahasiswa', 'kabinetMahasiswaJabatan.jabatan')->latest()->first();
        return view('page.marchandise_show', [
            'marchandise' => $marchandise,
            'kabinet' => $kabinet
        ]);
    }
}
