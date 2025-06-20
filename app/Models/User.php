<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Role;
// use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class);
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }
    public function isAdmin()
    {
        return $this->roles()->where('name', 'super_admin')->exists()
            || $this->email === 'l@l.com';
        // return $this->roles()->where('name', 'super_admin')->exists();
    }
}
