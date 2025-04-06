<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;

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
}
