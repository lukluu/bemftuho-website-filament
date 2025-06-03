<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, HasFactory;

    protected $table = 'mahasiswas';
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('mahasiswa')->singleFile()->useDisk('mahasiswa');
    }
    protected $with = ['kabinetMahasiswaJabatan.jabatan', 'kabinetMahasiswaJabatan.kabinet'];
    public function kabinetMahasiswaJabatan()
    {
        return $this->hasMany(KabinetMahasiswaJabatan::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($mahasiswa) {
            $mahasiswa->nama = Str::title($mahasiswa->nama);
        });
    }
}
