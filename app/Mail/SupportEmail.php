<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportEmail extends Mailable
{
    use Queueable,
        SerializesModels;

    public $inputs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = isset($this->inputs['email'])?$this->inputs['email']:"no-reply@". env('APP_DOMAIN');
        $name = isset($this->inputs['name'])?$this->inputs['name']:config('app.name');
        return $this->from($email, $name)
                        ->replyTo($email, $name)
                        ->subject(toTitleCase($this->inputs['subject']) . ' - ' . config('app.name'))
                        ->view('email.support');
    }
}
