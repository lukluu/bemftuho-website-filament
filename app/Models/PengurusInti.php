<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengurusInti extends Model
{
    protected $table = 'pengurus_intis';

    protected $fillable = ['jabatan', 'mahasiswa_id', 'kabinet_id'];
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kabinet()
    {
        return $this->belongsTo(Kabinet::class);
    }
}
