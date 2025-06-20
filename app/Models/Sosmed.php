<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sosmed extends Model
{
    /** @use HasFactory<\Database\Factories\SosmedFactory> */
    use HasFactory;

    protected $table = 'sosmeds';

    protected $guarded = ['id'];

    public function sosmedMahasiswas()
    {
        return $this->hasMany(SosmedMahasiswa::class);
    }
}
