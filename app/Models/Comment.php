<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'body',
    ];

    // Relazione con l'utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con il video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
