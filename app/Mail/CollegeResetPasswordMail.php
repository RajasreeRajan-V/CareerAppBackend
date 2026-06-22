<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollegeResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $collegeName;
    public string $resetUrl;

    public function __construct(string $collegeName, string $resetUrl)
    {
        $this->collegeName = $collegeName;
        $this->resetUrl    = $resetUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset Your College Portal Password');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.college_reset_password');
    }
}