<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $content;
    public $tries = 5;
    public $timeout = 60;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $order)
    {
        $this->content = $content;
        $this->order = $order;
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
        return $this->view('emails.order.confirmation');
    }
}
