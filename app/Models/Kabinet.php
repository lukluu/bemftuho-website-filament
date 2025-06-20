<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Kabinet extends Model
{
    protected $table = 'kabinets';

    protected $guarded = ['id'];

    public function kabinetMahasiswaJabatan()
    {
        return $this->hasMany(KabinetMahasiswaJabatan::class);
    }
    public function ketua()
    {
        return $this->hasOne(KabinetMahasiswaJabatan::class)
            ->whereHas('jabatan', fn($query) => $query->where('nama_jabatan', 'Ketua'))
            ->with('mahasiswa'); // agar kita bisa ambil nama mahasiswanya
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($kabinet) {
            $kabinet->slug = Str::slug($kabinet->nama_kabinet);
        });
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
