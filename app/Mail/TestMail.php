<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $this->afterCommit();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: $this->mailData['title'] ?? 'Test Email from SuperDuper Filament Starter',
        );

        // Add reply-to address if specified in settings
        if (!empty(config('mail.reply_to.address'))) {
            $envelope->replyTo(
                new Address(
                    config('mail.reply_to.address'),
                    config('mail.reply_to.name')
                )
            );
        }

        return $envelope;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
            with: [
                'mailData' => $this->mailData,
                'theme' => $this->mailData['theme'] ?? null,
                'preheader' => 'This is a test email to verify your email configuration is working properly.',
                'footerText' => $this->mailData['theme']['footer'] ?? ('© ' . date('Y') . ' SuperDuper Starter. All rights reserved.'),
                'displayDate' => now()->format('F j, Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Include a sample attachment only if specifically requested in mail data
        if (isset($this->mailData['include_sample_attachment']) && $this->mailData['include_sample_attachment']) {
            return [
                Attachment::fromData(fn () => 'This is a test attachment content', 'test-attachment.txt')
                    ->withMime('text/plain'),
            ];
        }

        return [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->withSymfonyMessage(function ($message) {
            $message->getHeaders()
                ->addTextHeader('X-Mail-Type', 'test')
                ->addTextHeader('X-Environment', app()->environment());
        });

        if (isset($this->mailData['priority'])) {
            $message->priority($this->mailData['priority']);
        }

        return $message;
    }
}
