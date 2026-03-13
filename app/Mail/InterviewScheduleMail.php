<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Application $application,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Guidance Interview Schedule | Samuel Christian College',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview_schedule',
        );
    }
}
