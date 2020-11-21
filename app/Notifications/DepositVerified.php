<?php

namespace App\Notifications;

use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function config;
use function route;
use function to_currency;

class DepositVerified extends Notification
{
    use Queueable;

    private $deposit;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Investment $deposit)
    {
        $this->deposit = $deposit;
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
        $amount = to_currency($this->deposit->amount);
        return (new MailMessage)
                        ->subject("Deposit Verified")
                        ->greeting('Hello, ' . $notifiable->name)
                        ->line("Your deposit of {$amount} with reference {$this->deposit->reference} has been verified")
//                        ->line("You can now continue other transactions")
                        ->action('Go to Dashboard', route('dashboard.investments'))
                        ->line('Thank you for using ' . config('app.name'));
    }

    public function toDatabase($notifiable)
    {
        $amount = to_currency($this->deposit->amount);
        return [
            'msg' => "Your deposit of {$amount} with reference {$this->deposit->reference} has been verified",
            'link' => route('dashboard.investments')
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
