<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
    ];

    public $timestamps = true; // serve per registrare la data della visualizzazione

    /**
     * Utente che ha visto il video
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Video visto
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
