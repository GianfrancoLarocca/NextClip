<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Models\VideoHistory;
use Illuminate\Support\Facades\Cache;
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

        // return response()->json($videos);
        return VideoResource::collection(
            Video::where('visibility', 'public')
                ->with('channel')
                ->latest()
                ->paginate(120)
        );        
    }

    public function show(Video $video)
    {
        // Visibilità pubblica o non in elenco
        if (!in_array($video->visibility, ['public', 'unlisted'])) {
            return response()->json(['message' => 'Video not available'], Response::HTTP_FORBIDDEN);
        }

        $user = request()->user();
        $viewerId = $user ? 'user_' . $user->id : 'ip_' . request()->ip();
        $cacheKey = "video_viewed_{$video->id}_{$viewerId}";

        // Se non è stato visto di recente, incrementa views
        if (!Cache::has($cacheKey)) {
            $video->increment('views');

            // Salva nel cache per 5 minuti
            Cache::put($cacheKey, true, now()->addMinutes(5));
        }

        $video->load('channel', 'likes');

        if ($user) {
            VideoHistory::updateOrCreate(
                ['user_id' => $user->id, 'video_id' => $video->id],
                ['updated_at' => now()]
            );
        }

        // return response()->json($video);
        return new VideoResource($video);
    }
}
