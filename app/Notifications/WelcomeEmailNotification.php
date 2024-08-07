<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Made Solution')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We are excited to welcome you to Made Solution!')
            ->line('Thank you for choosing our services. We are dedicated to providing you with the best solutions to meet your needs.')
            ->line('-----------------------------')
            ->line('We kindly request that you confirm your account so that you may access the website.')
            ->line('-----------------------------')
            ->line('If you have any questions or need assistance, feel free to reach out to our support team.')
            ->action('Visit Our Website', url('/'))
            ->line('Thank you for being a valued member of Made Solution.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
