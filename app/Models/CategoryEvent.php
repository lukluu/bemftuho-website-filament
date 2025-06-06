<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CategoryEvent extends Model
{
    protected $table = 'category_events';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->name = Str::slug($category->name);
        });
    }
}
