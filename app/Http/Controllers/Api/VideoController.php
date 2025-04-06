<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    /**
     * Elenco dei video pubblici più recenti
     */
    public function index()
    {
        $videos = Video::with('channel')
            ->where('visibility', 'public')
            ->orderByDesc('published_at')
            ->paginate(30);

        return response()->json($videos);
    }

    public function show(Video $video)
    {
        // Visibilità pubblica o non in elenco
        if (!in_array($video->visibility, ['public', 'unlisted'])) {
            return response()->json(['message' => 'Video not available'], Response::HTTP_FORBIDDEN);
        }

        $video->load('channel'); // Include dati del canale

        return response()->json($video);
    }
}
