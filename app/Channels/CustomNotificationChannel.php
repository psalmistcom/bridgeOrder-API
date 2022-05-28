<?php

namespace App\Channels;

use App\Services\Utility\CustomNotificationService;
use Illuminate\Notifications\Notification;

class CustomNotificationChannel
{
    public function send($notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toCustomNotification')) {
            $content = $notification->toCustomNotification($notifiable);
            CustomNotificationService::createNotification($notifiable, $content['subject'], $content['message']);
        }
    }
}
