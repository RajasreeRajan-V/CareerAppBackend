<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollegeRenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly object $college,
        public readonly int $daysLeft,
        public readonly string $expiryDate,
    ) {}

    /**
     * Email subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Careers Portal Subscription Expires in {$this->daysLeft} Day(s)",
        );
    }

    /**
     * Email content / blade view
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.college_renewal_reminder',
        );
    }
}