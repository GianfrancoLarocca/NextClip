<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Crea una segnalazione per un video
     */
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        $report = Report::create([
            'user_id' => Auth::id(),
            'video_id' => $video->id,
            'reason' => $request->input('reason'),
            'details' => $request->input('details'),
        ]);

        return response()->json([
            'message' => 'Segnalazione inviata con successo.',
            'report' => $report,
        ], 201);
    }
}
