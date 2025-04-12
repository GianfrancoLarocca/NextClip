<?php

namespace App\Notifications;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewLikeNotification extends Notification
{
    use Queueable;

    public function __construct(protected Video $video) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'like',
            'video_id' => $this->video->id,
            'video_title' => $this->video->title,
            'video_slug' => $this->video->slug,
            'liked_by' => auth()->user()?->name,
        ]);
    }
}
