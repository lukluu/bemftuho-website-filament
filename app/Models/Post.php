<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'posts';
    protected $guarded = ['id'];
    protected $casts = ['is_published' => 'boolean', 'tags' => 'array'];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            // Generate slug
            if ($post->isDirty('title')) {
                $post->slug = Str::slug($post->title);
                $post->user_id = Auth::id();
            }
        });
        static::updating(function ($post) {
            $originalUserId = $post->getOriginal('user_id');
            $post->user_id = $originalUserId; // kunci ke nilai lama
        });
    }
    // slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
