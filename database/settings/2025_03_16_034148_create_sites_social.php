<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites_social.facebook_url', '');
        $this->migrator->add('sites_social.twitter_url', '');
        $this->migrator->add('sites_social.instagram_url', '');
        $this->migrator->add('sites_social.linkedin_url', '');
        $this->migrator->add('sites_social.youtube_url', '');
        $this->migrator->add('sites_social.pinterest_url', '');
        $this->migrator->add('sites_social.tiktok_url', '');
        $this->migrator->add('sites_social.social_share_enabled', true);
        $this->migrator->add('sites_social.social_share_platforms', ['facebook', 'twitter', 'linkedin']);
        $this->migrator->add('sites_social.social_share_default_image', 'sites/share-image.png');
    }
};
