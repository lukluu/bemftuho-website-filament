<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatans';

    protected $guarded = ['id'];

    public function kabinetMahasiswaJabatan()
    {
        return $this->hasMany(KabinetMahasiswaJabatan::class);
    }

    // buat otomatis slug
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($jabatan) {
            $jabatan->slug = Str::slug($jabatan->nama_jabatan);
            $jabatan->nama_jabatan = Str::title($jabatan->nama_jabatan);
        });
    }
}
