<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
            $pengumuman->user_id = Auth::id();
        });
        static::updating(function ($pengumuman) {
            $originalUserId = $pengumuman->getOriginal('user_id');
            $pengumuman->user_id = $originalUserId; // kunci ke nilai lama
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
