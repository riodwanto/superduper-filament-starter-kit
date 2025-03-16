<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteScriptSettings extends Settings
{
    public ?string $header_scripts;
    public ?string $body_start_scripts;
    public ?string $body_end_scripts;
    public ?string $footer_scripts;
    public bool $cookie_consent_enabled;
    public ?string $cookie_consent_text;
    public ?string $cookie_consent_button_text;
    public ?string $cookie_consent_policy_url;
    public ?string $custom_css;
    public ?string $custom_js;

    public static function group(): string
    {
        return 'sites_scripts';
    }
}
