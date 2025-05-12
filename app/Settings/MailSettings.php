<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string $from_address;
    public string $from_name;
    public string $reply_to_address;
    public string $reply_to_name;

    // SMTP configuration
    public ?string $driver;
    public ?string $host;
    public int $port;
    public string $encryption;
    public ?string $username;
    public ?string $password;
    public ?int $timeout;
    public ?string $local_domain;

    // Email template and design settings
    public string $template_theme;
    public string $footer_text;
    public string $logo_path;
    public string $primary_color;
    public string $secondary_color;

    // Email delivery configuration
    public bool $queue_emails;
    public string $queue_name;
    public string $queue_connection;
    public array $rate_limiting;

    // Notification settings
    public bool $notifications_enabled;
    public array $notification_types;

    // Email testing and debugging
    public bool $test_mode;
    public string $log_channel;
    public string $test_to_address;

    // Alternative mail providers configuration
    public array $providers;

    public static function group(): string
    {
        return 'mail';
    }

    public static function encrypted(): array
    {
        return [
            'username',
            'password',
            'providers.mailgun.secret',
            'providers.postmark.token',
            'providers.ses.key',
            'providers.ses.secret',
        ];
    }

    public function loadMailSettingsToConfig($data = null): void
    {
        // Core mail configuration
        config([
            'mail.default' => $data['driver'] ?? $this->driver,
            'mail.mailers.smtp.host' => $data['host'] ?? $this->host,
            'mail.mailers.smtp.port' => $data['port'] ?? $this->port,
            'mail.mailers.smtp.encryption' => $data['encryption'] ?? $this->encryption,
            'mail.mailers.smtp.username' => $data['username'] ?? $this->username,
            'mail.mailers.smtp.password' => $data['password'] ?? $this->password,
            'mail.mailers.smtp.timeout' => $data['timeout'] ?? $this->timeout,
            'mail.mailers.smtp.local_domain' => $data['local_domain'] ?? $this->local_domain,
            'mail.from.address' => $data['from_address'] ?? $this->from_address,
            'mail.from.name' => $data['from_name'] ?? $this->from_name,
        ]);

        // Reply-to configuration
        if (isset($data['reply_to_address']) || $this->reply_to_address) {
            config([
                'mail.reply_to.address' => $data['reply_to_address'] ?? $this->reply_to_address,
                'mail.reply_to.name' => $data['reply_to_name'] ?? $this->reply_to_name,
            ]);
        }

        // Queue configuration
        if ($this->queue_emails) {
            config([
                'queue.connections.' . $this->queue_connection . '.queue' => $this->queue_name,
                'mail.queue.connection' => $this->queue_connection,
                'mail.queue.queue' => $this->queue_name,
            ]);
        }

        // Configure alternative mail providers if driver matches
        if ($this->driver === 'mailgun' && isset($this->providers['mailgun'])) {
            config([
                'services.mailgun.domain' => $this->providers['mailgun']['domain'],
                'services.mailgun.secret' => $this->providers['mailgun']['secret'],
                'services.mailgun.endpoint' => $this->providers['mailgun']['endpoint'],
            ]);
        } elseif ($this->driver === 'postmark' && isset($this->providers['postmark'])) {
            config([
                'services.postmark.token' => $this->providers['postmark']['token'],
            ]);
        } elseif ($this->driver === 'ses' && isset($this->providers['ses'])) {
            config([
                'services.ses.key' => $this->providers['ses']['key'],
                'services.ses.secret' => $this->providers['ses']['secret'],
                'services.ses.region' => $this->providers['ses']['region'],
            ]);
        }

        // Test mode configuration
        if ($this->test_mode) {
            config([
                'mail.to.address' => $this->test_to_address,
                'mail.to.name' => 'Test Recipient',
            ]);
        }
    }

    /**
     * Check if MailSettings is configured with necessary values.
     */
    public function isMailSettingsConfigured(): bool
    {
        // Basic configuration check
        $hasBasicConfig = $this->from_address && $this->from_name;

        // Driver-specific validation
        if ($this->driver === 'smtp') {
            return $hasBasicConfig && $this->host && $this->username && $this->password;
        } elseif ($this->driver === 'mailgun') {
            return $hasBasicConfig && isset($this->providers['mailgun']['domain']) && isset($this->providers['mailgun']['secret']);
        } elseif ($this->driver === 'postmark') {
            return $hasBasicConfig && isset($this->providers['postmark']['token']);
        } elseif ($this->driver === 'ses') {
            return $hasBasicConfig && isset($this->providers['ses']['key']) && isset($this->providers['ses']['secret']);
        }

        return $hasBasicConfig;
    }

    /**
     * Get email theme configuration for templates
     */
    public function getEmailThemeConfig(): array
    {
        return [
            'logo' => $this->logo_path,
            'colors' => [
                'primary' => $this->primary_color,
                'secondary' => $this->secondary_color,
            ],
            'footer' => $this->footer_text,
            'theme' => $this->template_theme,
        ];
    }

    /**
     * Check if a specific notification type is enabled
     */
    public function isNotificationTypeEnabled(string $type): bool
    {
        return $this->notifications_enabled &&
               isset($this->notification_types[$type]) &&
               $this->notification_types[$type];
    }

    /**
     * Get rate limiting configuration
     */
    public function getRateLimitingConfig(): array
    {
        return $this->rate_limiting;
    }
}
