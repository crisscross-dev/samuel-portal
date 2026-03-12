<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Application $application,
        public readonly Student $student,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admission Confirmed – Entrance Exam Schedule | Samuel Christian College',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admission_confirmed',
        );
    }
}
