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
            $baseSlug = Str::slug($channel->name);
            $slug = $baseSlug;
            $counter = 1;
        
            // Controlla se esiste già uno slug identico
            while (Channel::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
        
            $channel->slug = $slug;
        });        

        static::updating(function ($channel) {
            if ($channel->isDirty('name')) {
                $baseSlug = Str::slug($channel->name);
                $slug = $baseSlug;
                $counter = 1;
        
                // Ignora il canale corrente per evitare conflitto con se stesso
                while (Channel::where('slug', $slug)->where('id', '!=', $channel->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
        
                $channel->slug = $slug;
            }
        });
    }
}
