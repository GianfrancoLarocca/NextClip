<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    /**
     * Carica un file video nel filesystem
     */
    public function store(Request $request)
    {
        //  Autenticazione già gestita via middleware

        //  Validazione del file video
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg|max:51200', // max 50MB
        ]);

        // Salva il file nel disco pubblico
        $path = $request->file('video')->store('videos', 'public');

        return response()->json([
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
