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
        if (!in_array($video->visibility, ['public', 'unlisted'])) {
            return response()->json(['message' => 'Video not available'], Response::HTTP_FORBIDDEN);
        }

        $user = request()->user();
        $viewerId = $user ? 'user_' . $user->id : 'ip_' . request()->ip();
        $cacheKey = "video_viewed_{$video->id}_{$viewerId}";

        if (!Cache::has($cacheKey)) {
            $video->increment('views');

            Cache::put($cacheKey, true, now()->addMinutes(5));
        }

        $video->load('channel', 'likes');

        if ($user) {
            VideoHistory::updateOrCreate(
                ['user_id' => $user->id, 'video_id' => $video->id],
                ['updated_at' => now()]
            );
        }

        return new VideoResource($video);
    }

    public function related(Video $video)
    {
        $relatedByTags = Video::where('id', '!=', $video->id)
            ->where('visibility', 'public')
            ->whereHas('tags', function ($query) use ($video) {
                $query->whereIn('tags.id', $video->tags->pluck('id'));
            })
            ->with('channel')
            ->latest()
            ->take(10)
            ->get();

        if ($relatedByTags->isEmpty()) {
            $relatedByTags = Video::where('id', '!=', $video->id)
                ->where('channel_id', $video->channel_id)
                ->where('visibility', 'public')
                ->with('channel')
                ->latest()
                ->take(10)
                ->get();
        }

        return VideoResource::collection($relatedByTags);
    }

}
