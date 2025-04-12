<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewSubscriberNotification;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function subscribe(Channel $channel)
    {
        $user = User::findOrFail(Auth::id());

        // Evita iscrizione al proprio canale
        if ($channel->user_id === $user->id) {
            return response()->json(['message' => 'Non puoi iscriverti al tuo canale.'], 403);
        }

        // Evita doppie iscrizioni
        if (! $channel->subscribers()->where('user_id', $user->id)->exists()) {
            $channel->subscribers()->attach($user->id);
            $channel->user->notify(new NewSubscriberNotification($user));
        }

        return response()->json(['message' => 'Iscritto al canale.'], 200);
    }

    public function unsubscribe(Channel $channel)
    {
        Auth::user()->subscribedChannels()->detach($channel->id);

        return response()->json(['message' => 'Iscrizione rimossa.']);
    }
}
