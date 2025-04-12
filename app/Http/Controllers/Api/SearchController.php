<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string|max:255',
        ]);

        $query = $request->input('q');

        $videos = Video::where('visibility', 'public')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('channel')
            ->orderByDesc('published_at')
            ->paginate(30);

        return VideoResource::collection($videos);
    }
}
