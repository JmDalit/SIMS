<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $firstname;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $firstname)
    {
        $this->otp = $otp;
        $this->firstname = $firstname;
    }


    public function build()
    {
        return $this->subject('SIMS One-Time Password Verification')
            ->view(('otpMail'))
            ->with([
                'otp' => $this->otp,
                'firstname' => $this->firstname,
            ]);
    }
}
