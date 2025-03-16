<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public bool $is_maintenance;
    public string $name;
    public string $tagline;
    public string $description;
    public ?string $logo;
    public string $company_name;
    public string $company_email;
    public string $company_phone;
    public string $company_address;
    public string $default_language;
    public string $timezone;
    public string $copyright_text;
    public string $terms_url;
    public string $privacy_url;
    public string $cookie_policy_url;
    public string $custom_404_message;
    public string $custom_500_message;

    public static function group(): string
    {
        return 'sites';
    }
}
