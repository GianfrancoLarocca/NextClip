<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoHistoryController extends Controller
{
    /**
     * Aggiunge un video alla cronologia dell'utente
     */
    public function store(Video $video)
    {
        $user = Auth::user();

        // Se già presente, aggiorna solo il timestamp
        VideoHistory::updateOrCreate(
            ['user_id' => $user->id, 'video_id' => $video->id],
            ['updated_at' => now()]
        );

        return response()->json(['message' => 'Video aggiunto alla cronologia.']);
    }

    /**
     * Restituisce la cronologia dell'utente autenticato
     */
    public function index()
    {
        $user = Auth::user();

        $history = $user->videoHistory()
            ->with('video.channel')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return response()->json($history);
    }

    /**
     * Cancella un video dalla cronologia
     */
    public function destroy(Video $video)
    {
        Auth::user()->videoHistory()->detach($video->id);

        return response()->json(['message' => 'Video rimosso dalla cronologia.']);
    }
}
