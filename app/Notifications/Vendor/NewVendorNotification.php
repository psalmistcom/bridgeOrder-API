<?php

namespace App\Notifications\Vendor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVendorNotification extends Notification
{
    use Queueable;

    private $password;
    private $restaurant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password, $restaurant)
    {
        $this->password = $password;
        $this->restaurant = $restaurant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->full_name . '!')
            ->subject(' Welcome to Bridge Order')
            ->line('You have been added to the ' . $this->restaurant->name . ' team on the Bridge order app.')
            ->line('Log in with the following details')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password)
//            ->action('Login', url('/'))
            ->line('Thank you for using Bridge Order!');
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
