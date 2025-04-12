<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'title',
        'description',
        'video_path',
        'thumbnail_path',
        'slug',
        'visibility',
        'views',
        'duration',
        'published_at',
    ];

    /**
     * Relazione: un video appartiene a un canale
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'video_user_likes')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Genera uno slug unico in automatico
     */
    protected static function booted()
    {
        static::creating(function ($video) {
            $baseSlug = Str::slug($video->title);
            $slug = $baseSlug;
            $counter = 1;

            while (Video::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $video->slug = $slug;
        });

        static::updating(function ($video) {
            if ($video->isDirty('title')) {
                $baseSlug = Str::slug($video->title);
                $slug = $baseSlug;
                $counter = 1;

                while (Video::where('slug', $slug)->where('id', '!=', $video->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $video->slug = $slug;
            }
        });
    }
}
