<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailAboutAppointement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected String $date,
        protected String $senderName,
        protected String $recieverName,
        protected String $realtyName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointement Date',
        );
    }

    public function build()
    {
        return $this->from('no-reply@rentchicken.net', 'Zobra')
            ->view('mail.Appointement')
            ->with(['date' => $this->date])
            ->with(['senderName' => $this->senderName])
            ->with(['recieverName' => $this->recieverName])
            ->with(['realtyName' => $this->realtyName])
            ->subject('Appoimtent');
    }
}
