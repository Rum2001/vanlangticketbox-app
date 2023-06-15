<?php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $attendee;
    public $qrCode;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Attendee  $attendee
     * @param  string  $qrCode
     * @return void
     */
    public function __construct(Attendee $attendee, $qrCode)
    {
        $this->attendee = $attendee;
        $this->qrCode = $qrCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to the Event')
                    ->view('emails.welcome')
                    ->with([
                        'attendee' => $this->attendee,
                        'qrCode' => $this->qrCode,
                    ]);
    }
}
