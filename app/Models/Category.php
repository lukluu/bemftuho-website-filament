<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $guarded = ['id'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Mutator untuk name (otomatis dijalankan saat set value)
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = Str::title($value);
    // }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->slug = Str::slug($category->name);
            $category->name = Str::title($category->name);
        });
    }
}
