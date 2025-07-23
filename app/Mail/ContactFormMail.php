<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $contactData;

    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    public function envelope(): Envelope
    {
        $typeLabel = $this->getTypeLabel($this->contactData['type']);
        
        return new Envelope(
            subject: "[Faceit Scope] {$typeLabel} - {$this->contactData['subject']}",
            replyTo: $this->contactData['email'] !== 'Non fourni' ? [$this->contactData['email']] : null
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: $this->contactData
        );
    }

    private function getTypeLabel(string $type): string
    {
        $types = [
            'bug' => 'Bug Report',
            'suggestion' => 'Suggestion',
            'question' => 'Question',
            'feedback' => 'Feedback',
            'other' => 'Autre'
        ];

        return $types[$type] ?? 'Contact';
    }
}