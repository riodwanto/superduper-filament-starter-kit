<?php

namespace App\Listeners;

use App\Events\ContactUsCreated;
use App\Mail\NewContactUsNotificationMail;
use App\Settings\MailSettings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class SendNewContactNotification implements ShouldQueue
{
    use InteractsWithQueue;

    // TODO:LIST
    // - Migrate recipient configuration from Laravel config to Spatie settings
    // - Adding this notification email for Future feature "Config Checker"
    // - Add email template customization options (Low)
    // - Create admin UI for managing notification recipients (High)
    // - Add support for CC/BCC recipients (Low)
    // - Implement email delivery status tracking (Nice to Have)
    // - Add option to attach contact form data as PDF (Med)
    // - Create different notification templates based on contact form categories (Nice to Have)

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 60, 60]; // 1 minute, 5 minutes, 10 minutes

    /**
     * Create a new listener instance.
     */
    public function __construct(
        protected MailSettings $settings
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ContactUsCreated  $event
     * @return void
     */
    public function handle(ContactUsCreated $event)
    {
        $contact = $event->contact;

        if (!$this->settings->isMailSettingsConfigured()) {
            Log::warning('Mail settings not configured. Email not sent for contact ID: ' . $contact->id);
            $this->fail('Mail settings not properly configured');
            return;
        }

        $this->settings->loadMailSettingsToConfig();

        try {
            $recipients = Config::get('mail.contact_notification_recipients', []);

            if (empty($recipients)) {
                Log::warning('No recipients configured for contact notification. Using default.');
                $recipients = ['info@superduper.com'];
            }

            $mail = new NewContactUsNotificationMail($contact);

            Mail::to($recipients)->send($mail);

            Log::info('Contact notification email sent successfully', [
                'contact_id' => $contact->id,
                'recipients' => $recipients
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact notification email', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->fail($e); // Mark the job as failed for retries.
        }
    }
}
