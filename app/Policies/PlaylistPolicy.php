<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;

class PlaylistPolicy
{
    /**
     * Determina se l'utente può vedere una playlist.
     */
    public function view(User $user, Playlist $playlist): bool
    {
        return $playlist->user_id === $user->id;
    }

    /**
     * Determina se l'utente può aggiornare una playlist.
     */
    public function update(User $user, Playlist $playlist): bool
    {
        return $playlist->user_id === $user->id;
    }

    /**
     * Determina se l'utente può eliminare una playlist.
     */
    public function delete(User $user, Playlist $playlist): bool
    {
        return $playlist->user_id === $user->id;
    }
}
