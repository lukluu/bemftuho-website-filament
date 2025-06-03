<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KabinetMahasiswaJabatan extends Model
{
    use HasFactory;
    protected $table = 'kabinet_mahasiswa_jabatan';
    protected $guarded = ['id'];
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kabinet()
    {
        return $this->belongsTo(Kabinet::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
