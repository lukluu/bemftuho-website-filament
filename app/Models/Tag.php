<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($tag) {
            $tag->slug = Str::slug($tag->name);
            $tag->name = Str::title($tag->name);
        });
    }
}
