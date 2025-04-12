<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('q');

        $videos = Video::where('visibility', 'public')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%");
            })
            ->with('channel')
            ->latest()
            ->limit(20)
            ->get();

        $channels = Channel::where('name', 'like', "%$query%")
            ->orWhere('slug', 'like', "%$query%")
            ->limit(20)
            ->get();

        return response()->json([
            'videos' => $videos,
            'channels' => $channels,
        ]);
    }
}
