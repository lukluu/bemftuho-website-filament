<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SosmedMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'sosmed_mahasiswas';

    protected $guarded = ['id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function sosmed()
    {
        return $this->belongsTo(Sosmed::class);
    }
}
