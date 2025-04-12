<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;

class PublicPlaylistController extends Controller
{
    /**
     * Elenco delle playlist pubbliche più recenti
     */
    public function index()
    {
        $playlists = Playlist::where('is_public', true)
            ->withCount('videos')
            ->latest()
            ->paginate(30);

        return PlaylistResource::collection($playlists);
    }

    /**
     * Dettaglio di una playlist pubblica
     */
    public function show(Playlist $playlist)
    {
        abort_unless($playlist->is_public, 403);

        $playlist->load('user', 'videos.channel');

        return new PlaylistResource($playlist);
    }
}
