<?php

namespace App\Mail;

use App\Campus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampusEntered extends Mailable
{
    use Queueable, SerializesModels;

    protected $campusData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campusData)
    {
        $this->campusData = $campusData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->subject('new Update for School')
            ->view('mail_message')
            ->with('campusData', $this->campusData);
    }
}
