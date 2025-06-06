<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Kelembagaan extends Model
{
    use HasFactory;
    protected $table = 'kelembagaans';

    protected $guarded = ['id'];
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($kelembagaan) {
            $kelembagaan->name = Str::title($kelembagaan->name);
            $kelembagaan->slug = Str::slug($kelembagaan->name);
        });
    }
}
