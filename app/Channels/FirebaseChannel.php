<?php

namespace App\Channels;

use App\Services\Utility\Firebase;
use Illuminate\Notifications\Notification;

class FirebaseChannel
{
    public function send($notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toFirebase')) {
            $content = $notification->toFirebase($notifiable);

            Firebase::notify($notifiable, $content['custom_notification_id'], $content['subject'], $content['message']);
        }
    }
}
