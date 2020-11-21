<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification
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
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
//        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Welcome")
            ->greeting("Welcome, {$notifiable->first_name}")
            ->line("You are now a partner in one of the most promising ventures of the decade. "
                ."We have seen remarkable growth during the past year and we project even more significant profits.")
            ->line("Visit your dashboard to get started with investing easily.")
            ->action('Get Started', route('dashboard.invest'))
            ->line('Thank you for choosing ' . config('app.name'));
    }

    /**
     * Get the database representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'msg' => "Welcome {$notifiable->firstName}, click here to get started with investing easily",
            'link' => route('dashboard.invest')
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
