<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Elenco delle playlist dell'utente autenticato
     */
    public function index()
    {
        return Playlist::where('user_id', Auth::id())->get();
    }

    /**
     * Crea una nuova playlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        $playlist = Auth::user()->playlists()->create($validated);

        return response()->json($playlist, 201);
    }

    /**
     * Mostra una playlist specifica
     */
    public function show(Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        return response()->json($playlist);
    }

    /**
     * Aggiorna una playlist
     */
    public function update(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        $playlist->update($validated);

        return response()->json($playlist);
    }

    /**
     * Elimina una playlist
     */
    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $playlist->delete();

        return response()->noContent();
    }
}
