<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabinet extends Model
{
    protected $table = 'kabinets';

    protected $guarded = ['id'];

    public function kabinetMahasiswaJabatan()
    {
        return $this->hasMany(KabinetMahasiswaJabatan::class);
    }
}
