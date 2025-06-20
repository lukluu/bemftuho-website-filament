<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Filament\Panel\Concerns\HasGlobalSearch;
use App\Filament\Resources\MahasiswaResource;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends model implements HasMedia
{
    use InteractsWithMedia, HasFactory, HasGlobalSearch;

    protected $table = 'mahasiswas';
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

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

    public function sosmedMhs()
    {
        return $this->hasMany(SosmedMahasiswa::class);
    }
}
