<?php

namespace App\Notifications\Vendor;

use App\Models\Vendor\Restaurant;
use App\Models\Vendor\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Restaurant $restaurant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param Vendor $notifiable
     * @return array
     */
    public function via(Vendor $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Vendor $notifiable
     * @return MailMessage
     */
    public function toMail(Vendor $notifiable)
    {
        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->full_name . '!')
            ->subject(' Welcome to Bridge
            Order')
            ->line(
                'You have successfully registered ' . $this->restaurant->name .
                 ' as a restaurant on the bridge order app.'
            )
            ->line('Kindly hold on while your application is being processed');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
