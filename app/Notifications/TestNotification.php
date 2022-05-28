<?php

namespace App\Notifications;

use App\Channels\CustomNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomNotificationChannel::class];
    }

    /**
     *
     * @param mixed $notifiable
     */
    public function toCustomNotification($notifiable)
    {
        $subject = 'Vendor Access';
        $message = 'This is the way we store notifications in the database';
        return [
            'subject' => $subject,
            'message' => $message
        ];
    }
}
