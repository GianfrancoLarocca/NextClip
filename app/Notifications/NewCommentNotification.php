<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'video_id' => $this->comment->video_id,
            'video_slug' => $this->comment->video->slug,
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'comment_author' => $this->comment->user->name,
        ]);
    }
}
