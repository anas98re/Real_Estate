<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected int $verified) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail Verification',
        );
    }

    public function build()
    {
        return $this->from('no-reply@rentchicken.net', 'Zobra')
            ->view('mail.Verification')
            ->with(['verified' => $this->verified])
            ->subject('Verification Code');
    }
}
