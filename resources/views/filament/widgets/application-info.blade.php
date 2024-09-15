<x-filament-widgets::widget>
    <x-filament::section>
        <p class="text-base font-semibold leading-6 text-gray-950 dark:text-white">{{ config('app.name') }}</p>
        <p style="margin-top: 2px;margin-bottom: 2px;" class="text-xs text-gray-500 dark:text-gray-400">{{
            env('APP_VERSION') ? "v".env('APP_VERSION'): '' }}</p>
    </x-filament::section>
</x-filament-widgets::widget>
