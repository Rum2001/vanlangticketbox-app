<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $status;

    public function __construct(Event $event, $status)
    {
        $this->event = $event;
        $this->status = $status;
    }

    public function build()
    {
        return $this->view('emails.status-update')
                    ->subject('Event Status Update');
    }
}
