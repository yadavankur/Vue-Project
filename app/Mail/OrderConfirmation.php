<?php

namespace App\Mail;

use App\Models\Entities\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmation extends Mailable implements ShouldQueue
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
        // before creating new record into queue
        // create a new record into email table as well
        // then
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
        // $this->from($this->content['from'], $this->content['fromName']);
//        $this->to($this->content['to'], $this->content['toName']);
//        $this->cc($this->content['cc'], $this->content['ccName']);
//        $this->replyTo($this->content['replyTo'], $this->content['replyToName']);
//        $this->subject($this->content['title']);
        $this->to($this->content['to']);
        $this->cc($this->content['cc']);
        $assetIds = $this->content['attachedAssetIds'];
        if (count($assetIds) > 0)
        {
            $assets = Asset::wherein('asset_id', $assetIds)->where('active', 1)->get();
            foreach($assets as $asset)
            {
                $this->attach($asset->path, ['as' => $asset->filename, 'mime' => $asset->mimeType]);
            }
        }
        return $this->view('emails.order.confirmation');
    }


}
