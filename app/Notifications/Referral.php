<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Referral extends Notification
{
    use Queueable;

    protected $referred;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $referred)
    {
        $this->referred = $referred;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
//        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                        ->subject("New Referral")
                        ->greeting('Hello, ' . $notifiable->name)
                        ->line('You have a new referral on your list')
                        ->line('Name: ' . $this->referred->name)
                        ->action('View', route('dashboard.referrals'))
                        ->line('Thank you for using ' . config('app.name'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'msg' => "You have one new referral {$this->referred->name}",
            'link' => route('dashboard.referrals')
        ];
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
