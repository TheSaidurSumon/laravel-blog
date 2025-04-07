<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_pic',
    ];

    protected $hidden = ['password', 'remember_token'];

    // Relationships
    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function bookmarks() {
        return $this->hasMany(Bookmark::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}