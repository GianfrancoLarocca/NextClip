<?php

namespace App\Notifications;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Il tuo video "' . $this->video->title . '" ha ricevuto un nuovo like.',
            'video_id' => $this->video->id,
            'video_slug' => $this->video->slug,
        ];
    }
}
