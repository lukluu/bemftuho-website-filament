<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'posts';
    protected $fillable = ['title', 'slug', 'content', 'is_published', 'category_id'];
    protected $casts = ['is_published' => 'boolean', 'tags' => 'array'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posts')->singleFile()->useDisk('posts');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            $post->slug = Str::slug($post->title);
            $post->title = Str::title($post->title);
        });
    }
}
