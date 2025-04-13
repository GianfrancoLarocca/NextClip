<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedVideos()
    {
        return $this->belongsToMany(Video::class, 'video_user_likes')->withTimestamps();
    }

    public function subscribedChannels()
    {
        return $this->belongsToMany(Channel::class, 'channel_user_subscriptions')->withTimestamps();
    }

    public function videoHistory()
    {
        return $this->belongsToMany(Video::class, 'video_histories')->withTimestamps();
    }

    public function savedVideos()
    {
        return $this->belongsToMany(Video::class, 'saved_videos')->withTimestamps();
    }

}
