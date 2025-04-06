<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    /**
     * Carica un file video e una thumbnail opzionale
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg|max:51200',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $videoFile = $request->file('video');

        // Verifica se il file è stato caricato correttamente
        if (! $videoFile->isValid()) {
            return response()->json([
                'message' => 'File video non valido.',
                'error' => $videoFile->getErrorMessage(),
            ], 422);
        }

        // Salva il video
        $videoPath = $videoFile->store('videos', 'public');

        // Salva la thumbnail, se presente
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        return response()->json([
            'video_path' => $videoPath,
            'video_url' => Storage::disk('public')->url($videoPath),
            'thumbnail_path' => $thumbnailPath,
            'thumbnail_url' => $thumbnailPath ? Storage::disk('public')->url($thumbnailPath) : null,
        ]);
    }
}
