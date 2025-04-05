<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    /**
     * Mostra tutti i canali dell'utente autenticato
     */
    public function index()
    {
        return Auth::user()->channels()->get();
    }

    /**
     * Crea un nuovo canale per l'utente autenticato
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|string',
            'banner' => 'nullable|string',
        ]);

        $channel = Auth::user()->channels()->create($validated);

        return response()->json($channel, 201);
    }

    /**
     * Mostra un singolo canale
     */
    public function show(Channel $channel)
    {
        return $channel;
    }

    /**
     * Aggiorna un canale (solo se appartiene all'utente)
     */
    public function update(Request $request, Channel $channel)
    {
        $this->authorize('update', $channel); // TODO: aggiungere le policy

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|string',
            'banner' => 'nullable|string',
        ]);

        $channel->update($validated);

        return response()->json($channel);
    }

    /**
     * Elimina un canale
     */
    public function destroy(Channel $channel)
    {
        $this->authorize('delete', $channel);

        $channel->delete();

        return response()->noContent();
    }
}