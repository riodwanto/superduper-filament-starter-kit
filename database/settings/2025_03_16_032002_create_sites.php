<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.is_maintenance', false);
        $this->migrator->add('sites.name', 'SuperDuper Filament Starter Kit Website');
        $this->migrator->add('sites.logo', 'sites/logo.png');
        $this->migrator->add('sites.tagline', 'Starting point to kickstart your next project');
        $this->migrator->add('sites.description', "A starting point to create your next Filament 3 💡 app. With pre-installed plugins, pre-configured, and custom page. So you don't start all over again.");
        $this->migrator->add('sites.default_language', 'en');
        $this->migrator->add('sites.timezone', 'UTC');
        $this->migrator->add('sites.copyright_text', '© ' . date('Y') . ' Company Name. All rights reserved.');
        $this->migrator->add('sites.terms_url', '/terms');
        $this->migrator->add('sites.privacy_url', '/privacy');
        $this->migrator->add('sites.cookie_policy_url', '/cookie-policy');
        $this->migrator->add('sites.custom_404_message', 'Sorry, the page you are looking for could not be found.');
        $this->migrator->add('sites.custom_500_message', 'Sorry, something went wrong on our end.');
        $this->migrator->add('sites.company_name', 'Company Name');
        $this->migrator->add('sites.company_email', 'contact@example.com');
        $this->migrator->add('sites.company_phone', '+1234567890');
        $this->migrator->add('sites.company_address', '123 Main Street, City, Country');
    }
};
