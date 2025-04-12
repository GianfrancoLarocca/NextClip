<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Restituisce tutte le playlist dell'utente autenticato.
     */
    public function index()
    {
        return Auth::user()->playlists()->latest()->get();
    }

    /**
     * Crea una nuova playlist per l'utente autenticato.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist = Auth::user()->playlists()->create($data);

        return response()->json($playlist, 201);
    }

    /**
     * Restituisce una singola playlist.
     */
    public function show(Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        return response()->json($playlist);
    }

    /**
     * Aggiorna una playlist.
     */
    public function update(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist->update($data);

        return response()->json($playlist);
    }

    /**
     * Elimina una playlist.
     */
    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $playlist->delete();

        return response()->noContent();
    }
}
