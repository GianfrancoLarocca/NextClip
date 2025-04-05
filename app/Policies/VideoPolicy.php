<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Video $video): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * L'utente può aggiornare solo video dei propri canali
     */
    public function update(User $user, Video $video): bool
    {
        return $user->id === $video->channel->user_id;
    }

    /**
     * L'utente può eliminare solo video dei propri canali
     */
    public function delete(User $user, Video $video): bool
    {
        return $user->id === $video->channel->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Video $video): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Video $video): bool
    {
        return false;
    }
}
