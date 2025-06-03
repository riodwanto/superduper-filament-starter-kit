<?php

namespace App\Listeners\Impersonate;

use Illuminate\Support\Facades\Session;
use Filament\Facades\Filament;

class ImpersonateClearAuthHashes
{
    public function handle($event): void
    {
        $guardKey = config('laravel-impersonate.session_guard_using');
        $defaultGuard = config('laravel-impersonate.default_impersonator_guard');
        $panelGuard = Filament::getCurrentPanel()?->getAuthGuard() ?? $defaultGuard;

        $keys = [
            'password_hash_' . session('impersonate.guard'),
            'password_hash_' . session($guardKey),
            'password_hash_' . $panelGuard,
            'password_hash_' . \Illuminate\Support\Facades\Auth::getDefaultDriver(),
            'password_hash_sanctum',
        ];

        Session::forget(array_unique($keys));
    }
}
