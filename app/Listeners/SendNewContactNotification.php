<?php

namespace App\Listeners;

use App\Events\ContactUsCreated;
use App\Mail\NewContactNotificationMail;
// use App\Models\User;
use App\Settings\MailSettings;
// use App\Notifications\NewContactNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewContactNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\ContactUsCreated  $event
     * @return void
     */
    public function handle(ContactUsCreated $event)
    {
        $contact = $event->contact;
        $settings = app(MailSettings::class);

        if (!$settings->isMailSettingsConfigured()) {
            return;
        }

        $settings->loadMailSettingsToConfig(); // Load config from MailSettings

        // TODO: Configable ContactUs notification email
        Mail::to(["info@superduper.com"])
            ->send(new NewContactNotificationMail($contact));
    }
}
