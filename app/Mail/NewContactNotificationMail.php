<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactNotificationMail extends Mailable
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
            ->subject('New Contact')
            ->replyTo($this->contact->email, $this->contact->name)
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()
                    ->addTextHeader('X-Mailer', 'PHP/' . phpversion());
            });
    }
}
