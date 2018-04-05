<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SingleConfirmationText extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 60;
    public $content;
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
        $this->subject('Confirmation');
        return $this->view('emails.order.single-confirmation-text');
    }
}
