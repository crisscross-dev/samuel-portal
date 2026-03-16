<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAccountReleasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Application $application,
        public readonly Student $student,
        public readonly User $user,
        public readonly string $temporaryPassword,
        public readonly string $portalUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Portal Account Released | Samuel Christian College',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student_account_released',
        );
    }
}
