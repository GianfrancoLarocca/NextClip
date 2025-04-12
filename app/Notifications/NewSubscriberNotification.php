<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewSubscriberNotification extends Notification
{
    use Queueable;

    public function __construct(public User $subscriber) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'subscriber_id' => $this->subscriber->id,
            'subscriber_name' => $this->subscriber->name,
        ]);
    }
}
