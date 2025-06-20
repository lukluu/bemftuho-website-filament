<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Marchandise extends Model
{
    use HasFactory;
    protected $table = 'marchandises';

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($marchandise) {
            $marchandise->name = Str::title($marchandise->name);
            $marchandise->slug = Str::slug($marchandise->name);
        });
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }
}
