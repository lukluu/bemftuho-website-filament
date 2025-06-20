<?php

namespace App\Models;

use App\Models\Kabinet;
use Illuminate\Support\Str;
use App\Models\CategoryEvent;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;
    protected $table = 'events';
    protected $guarded = ['id'];
    // app/Models/Event.php
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumentasi')->useDisk('public');
    }
    public function kabinet()
    {
        return $this->belongsTo(Kabinet::class);
    }
    public function category()
    {
        return $this->belongsTo(CategoryEvent::class, 'category_event_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            $event->title = Str::title($event->title);
            $event->slug = Str::slug($event->title);
            $event->user_id = Auth::id();
        });
        static::updating(function ($event) {
            $originalUserId = $event->getOriginal('user_id');
            $event->user_id = $originalUserId; // kunci ke nilai lama
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
