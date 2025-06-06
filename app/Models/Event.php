<?php

namespace App\Models;

use App\Models\Kabinet;
use Illuminate\Support\Str;
use App\Models\CategoryEvent;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'events';
    protected $guarded = ['id'];
    // app/Models/Event.php
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('events')->singleFile();
    }
    public function kabinet()
    {
        return $this->belongsTo(Kabinet::class);
    }
    public function category()
    {
        return $this->belongsTo(CategoryEvent::class, 'category_event_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            $event->title = Str::title($event->title);
            $event->slug = Str::slug($event->title);
        });
    }
}
