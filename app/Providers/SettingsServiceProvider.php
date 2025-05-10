<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Settings\GeneralSettings;
use App\Settings\SiteSettings;
use App\Settings\SiteSeoSettings;
use App\Settings\SiteSocialSettings;
use App\Settings\SiteScriptSettings;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with([
                'generalSettings' => app(GeneralSettings::class),
                'siteSettings' => app(SiteSettings::class),
                'seoSettings' => app(SiteSeoSettings::class),
                'siteSocialSettings' => app(SiteSocialSettings::class),
                'scriptSettings' => app(SiteScriptSettings::class),
            ]);
        });
    }
}
