<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
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
        $channel = $user->channels()->firstOrFail(); // assumiamo che l'utente abbia almeno un canale

        // Salva i file
        $videoPath = $request->file('video')->store('videos', 'public');

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Calcola la durata del video (in secondi)
        $ffmpegOutput = null;
        $videoFullPath = Storage::disk('public')->path($videoPath);

        $escapedPath = escapeshellarg($videoFullPath);
        exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $escapedPath", $ffmpegOutput);

        $duration = isset($ffmpegOutput[0]) ? (int) round(floatval($ffmpegOutput[0])) : null;

        // Crea il record nel database
        $video = $channel->videos()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => Str::slug($request->input('title')) . '-' . uniqid(),
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'visibility' => $request->input('visibility', 'private'),
            'duration' => $duration,
            'published_at' => now(),
            'views' => 0,
        ]);          

        return (new VideoResource($video->load('channel')))
        ->additional(['message' => 'Video caricato con successo.']);
    }
}
