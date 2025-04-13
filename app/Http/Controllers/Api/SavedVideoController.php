<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedVideoController extends Controller
{
    /**
     * Restituisce la lista dei video salvati dall'utente
     */
    public function index()
    {
        $videos = Auth::user()
            ->savedVideos()
            ->with('channel')
            ->latest('saved_videos.created_at')
            ->paginate(20);

        return VideoResource::collection($videos);
    }

    /**
     * Aggiunge un video alla lista "Guarda più tardi"
     */
    public function store(Video $video)
    {
        Auth::user()->savedVideos()->syncWithoutDetaching([$video->id]);

        return response()->json(['message' => 'Video salvato con successo.']);
    }

    /**
     * Rimuove un video dalla lista "Guarda più tardi"
     */
    public function destroy(Video $video)
    {
        Auth::user()->savedVideos()->detach($video->id);

        return response()->json(['message' => 'Video rimosso dalla lista.']);
    }
}
