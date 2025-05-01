<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactUsNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact.notification')
                    ->subject('New Contact Form Submission')
                    ->replyTo($this->contact->email, "{$this->contact->firstname} {$this->contact->lastname}")
                    ->withSwiftMessage(function ($message) {
                        $message->getHeaders()
                                ->addTextHeader('X-Mailer', 'PHP/' . phpversion());
                    });
    }
}
