<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;

class PlaylistVideoController extends Controller
{
    /**
     * Restituisce tutti i video di una playlist
     */
    public function index(Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        return response()->json($playlist->videos()->with('channel')->get());
    }

    /**
     * Aggiunge un video alla playlist
     */
    public function store(Request $request, Playlist $playlist, Video $video)
    {
        $this->authorize('update', $playlist);

        // Evita doppie associazioni
        if (!$playlist->videos()->where('video_id', $video->id)->exists()) {
            $playlist->videos()->attach($video->id);
        }

        return response()->json(['message' => 'Video aggiunto alla playlist.']);
    }

    /**
     * Rimuove un video dalla playlist
     */
    public function destroy(Playlist $playlist, Video $video)
    {
        $this->authorize('update', $playlist);

        $playlist->videos()->detach($video->id);

        return response()->json(['message' => 'Video rimosso dalla playlist.']);
    }
}
