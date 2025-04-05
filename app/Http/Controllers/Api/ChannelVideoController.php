<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChannelVideoController extends Controller
{

    use AuthorizesRequests;

    /**
     * Elenca tutti i video di un canale
     */
    public function index(Channel $channel)
    {
        return $channel->videos()->get();
    }

    /**
     * Crea un nuovo video per un canale
     */
    public function store(Request $request, Channel $channel)
    {
        $this->authorize('update', $channel);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_path' => 'required|string',
            'thumbnail_path' => 'nullable|string',
            'visibility' => 'in:public,private,unlisted',
            'duration' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        $video = $channel->videos()->create($validated);

        return response()->json($video, 201);
    }

    /**
     * Mostra un video specifico del canale
     */
    public function show(Channel $channel, Video $video)
    {
        return $video;
    }

    /**
     * Aggiorna un video (solo se appartiene al canale dell'utente)
     */
    public function update(Request $request, Channel $channel, Video $video)
    {
        $this->authorize('update', $video);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'visibility' => 'in:public,private,unlisted',
            'duration' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        $video->update($validated);

        return response()->json($video);
    }

    /**
     * Elimina un video
     */
    public function destroy(Channel $channel, Video $video)
    {
        $this->authorize('delete', $video);

        $video->delete();

        return response()->noContent();
    }
}
