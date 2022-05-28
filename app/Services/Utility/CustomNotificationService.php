<?php

namespace App\Services\Utility;

class CustomNotificationService
{
    public static function createNotification(mixed $notifiable, string $subject, string $message): void
    {
        $notifiable->customNotifications()->create([
            'title' => $subject,
            'data' => $message,
        ]);
    }
}
