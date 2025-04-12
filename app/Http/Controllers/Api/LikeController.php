<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Notifications\NewLikeNotification;

class LikeController extends Controller
{
    /**
     * Aggiunge o rimuove un like da un video
     */
    public function toggle(Request $request, Video $video)
    {
        $user = $request->user();

        // Se l'utente ha già messo like, lo rimuove
        if ($video->likes()->where('user_id', $user->id)->exists()) {
            $video->likes()->detach($user->id);

            return response()->json([
                'liked' => false,
                'likes_count' => $video->likes()->count(),
            ]);
        }

        // Altrimenti aggiunge il like
        $video->likes()->attach($user->id);

        // Invia una notifica solo se non è il proprietario del video
        if ($video->channel->user_id !== $user->id) {
            $video->channel->user->notify(new NewLikeNotification($video));
        }

        return response()->json([
            'liked' => true,
            'likes_count' => $video->likes()->count(),
        ]);
    }
}
