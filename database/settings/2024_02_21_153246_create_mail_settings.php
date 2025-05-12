<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail.from_address', 'notifications@superduperstarter.com');
        $this->migrator->add('mail.from_name', 'SuperDuper Filament Starter');
        $this->migrator->add('mail.reply_to_address', 'support@superduperstarter.com');
        $this->migrator->add('mail.reply_to_name', 'SuperDuper Support');

        $this->migrator->add('mail.driver', 'smtp');
        $this->migrator->add('mail.host', null);
        $this->migrator->add('mail.port', 587);
        $this->migrator->add('mail.encryption', 'tls');
        $this->migrator->addEncrypted('mail.username', null);
        $this->migrator->addEncrypted('mail.password', null);
        $this->migrator->add('mail.timeout', 30);
        $this->migrator->add('mail.local_domain', null); // Local domain for HELO command, usually not needed unless behind proxy

        $this->migrator->add('mail.template_theme', 'default');
        $this->migrator->add('mail.footer_text', '© ' . date('Y') . ' SuperDuper Starter. All rights reserved.');
        $this->migrator->add('mail.logo_path', 'sites/email-logo.png');
        $this->migrator->add('mail.primary_color', '#2D2B8D');
        $this->migrator->add('mail.secondary_color', '#FFC903');

        $this->migrator->add('mail.queue_emails', true);
        $this->migrator->add('mail.queue_name', 'emails');
        $this->migrator->add('mail.queue_connection', 'database');
        $this->migrator->add('mail.rate_limiting', [
            'enabled' => true,
            'attempts' => 5,
            'per_minutes' => 1,
        ]);

        $this->migrator->add('mail.notifications_enabled', true);
        $this->migrator->add('mail.notification_types', [
            'account' => true,
            'system' => true,
            'marketing' => false,
            'blog' => false,
        ]);

        $this->migrator->add('mail.test_mode', false);
        $this->migrator->add('mail.log_channel', 'stack');
        $this->migrator->add('mail.test_to_address', null);

        $this->migrator->add('mail.providers', [
            'mailgun' => [
                'domain' => null,
                'secret' => null,
                'endpoint' => 'api.mailgun.net',
            ],
            'postmark' => [
                'token' => null,
            ],
            'ses' => [
                'key' => null,
                'secret' => null,
                'region' => 'us-east-1',
            ],
        ]);
    }
};
