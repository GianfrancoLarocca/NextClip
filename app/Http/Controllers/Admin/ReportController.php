<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Elenco delle segnalazioni recenti
     */
    public function index()
    {
        $reports = Report::with('video.channel', 'user')
            ->latest()
            ->paginate(30);

        return response()->json($reports);
    }

    /**
     * Elimina una segnalazione
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json(['message' => 'Segnalazione eliminata.']);
    }
}
