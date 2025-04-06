<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Illuminate\Support\Str;

class VideoUploadController extends Controller
{
    /**
     * Carica un file video e una thumbnail opzionale
     */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private,unlisted',
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg|max:51200',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);        

        $user = $request->user();
        $channel = $user->channels()->firstOrFail(); // 🎯 assumiamo che l'utente abbia almeno un canale

        // Salva i file
        $videoPath = $request->file('video')->store('videos', 'public');

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Crea il record nel database
        $video = $channel->videos()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => Str::slug($request->input('title')) . '-' . uniqid(),
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'visibility' => $request->input('visibility', 'private'),
            'duration' => null,
            'published_at' => now(),
            'views' => 0,
        ]);          

        return response()->json([
            'message' => 'Video caricato con successo.',
            'video' => $video,
        ], 201);
    }
}
