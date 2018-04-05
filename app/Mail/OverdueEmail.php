<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OverdueEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content;
    public $tries = 5;
    public $timeout = 60;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->content['to']);
        $this->cc($this->content['cc']);
        $this->subject($this->content['subject']);
        return $this->markdown('emails.notification.overdue');
    }
}
