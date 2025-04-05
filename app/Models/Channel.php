<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Channel extends Model
{
    use HasFactory;

    // Campi assegnabili in massa
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'avatar',
        'banner',
        'slug',
    ];

    /**
     * Relazione: un canale appartiene a un utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generazione automatica dello slug al momento della creazione o aggiornamento
     */
    protected static function booted()
    {
        static::creating(function ($channel) {
            $channel->slug = Str::slug($channel->name);
        });

        static::updating(function ($channel) {
            if ($channel->isDirty('name')) {
                $channel->slug = Str::slug($channel->name);
            }
        });
    }
}
