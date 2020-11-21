<?php

namespace App\Notifications;

use App\Models\BonusLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Bonus extends Notification
{
    use Queueable;

    protected $bonus;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(BonusLog $bonus)
    {
        $this->bonus = $bonus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $s = $this->bonus->amount === 1 ? '' : 's';
        return (new MailMessage)
                        ->subject("{$this->bonus->name}")
                        ->greeting("Congratulations, {$notifiable->name}")
                        ->line(to_currency($this->bonus->amount) . " has been added to your balance.")
                        ->action('View', route('dashboard.withdrawals'))
                        ->line('Thank you for using ' . config('app.name'));
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'msg' => "{$this->bonus->name}: Congratulations, "
            . to_currency($this->bonus->amount)
            . " has been added to your balance.",
            'link' => route('dashboard.withdrawals')
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
