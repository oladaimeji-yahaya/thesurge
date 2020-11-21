<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function config;
use function route;
use function to_currency;

class WithdrawalRequestApproved extends Notification
{
    use Queueable;
    private $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
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
        $amount = to_currency($this->withdrawal->amount);
        return (new MailMessage)
                        ->subject("Withdrawal Request Approved")
                        ->greeting('Hello, ' . $notifiable->name)
                        ->line("Your withdrawal request of {$amount} with reference {$this->withdrawal->reference} has been approved")
//                        ->line("You can now perform other transactions")
                        ->action('Go to Dashboard', route('dashboard.withdrawals'))
                        ->line('Thank you for using ' . config('app.name'));
    }

    public function toDatabase($notifiable)
    {
        $amount = to_currency($this->withdrawal->amount);
        return [
            'msg' => "Your withdrawal request of {$amount} with reference {$this->withdrawal->reference} has been approved",
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
