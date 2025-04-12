<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Models\VideoHistory;
use Illuminate\Support\Facades\Cache;
use Request;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    /**
     * Elenco dei video pubblici più recenti
     */
    public function index(Request $request)
    {
        $query = Video::where('visibility', 'public')
            ->with('channel', 'tags')
            ->latest();

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        return VideoResource::collection($query->paginate(30));
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
