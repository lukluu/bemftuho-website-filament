<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{

    use HasFactory;
    protected $table = 'pengumumans';

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pengumuman) {
            $pengumuman->title = Str::title($pengumuman->title);
            $pengumuman->slug = Str::slug($pengumuman->title);
        });
    }
}
