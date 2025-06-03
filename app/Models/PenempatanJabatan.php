<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanJabatan extends Model
{
    protected $table = 'penempatan_jabatan';
    protected $guarded = ['id'];

    public function kabinet()
    {
        return $this->belongsTo(Kabinet::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
