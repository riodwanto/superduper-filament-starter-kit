<x-filament-widgets::widget class="border-l-4 border-primary-400 rounded-2xl fi-filament-info-widget bg-gradient-to-r from-secondary-50 to-white dark:from-secondary-900 dark:to-gray-800">
    <x-filament::section>
        <div class="flex items-center gap-x-6 gap-y-0">
            <div class="flex-1">
                <div class="flex items-center gap-x-2">
                    <span class="text-xl font-bold text-primary-600 dark:text-primary-100">{{ config('app.name') }}</span>
                    <span class="text-xs bg-primary-100 text-primary-600 px-2 py-0.5 rounded">v{{ env('APP_VERSION') }}</span>
                </div>
                <div class="flex gap-x-3">
                    <a href="https://github.com/riodwanto/superduper-filament-starter-kit" target="_blank" class="flex items-center justify-center gap-1 text-sm font-semibold text-neutral-800 dark:text-neutral-400 hover:text-primary-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.021c0 4.428 2.865 8.184 6.839 9.504.5.092.682-.217.682-.483
                                0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608
                                1.004.07 1.532 1.032 1.532 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.339-2.22-.253-4.555-1.113-4.555-4.951
                                0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.025A9.564 9.564 0 0 1 12 6.844c.85.004
                                1.705.115 2.504.337 1.909-1.295 2.748-1.025 2.748-1.025.546 1.378.202 2.397.1 2.65.64.7 1.028 1.595 1.028 2.688
                                0 3.848-2.338 4.695-4.566 4.944.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.749 0 .268.18.579.688.481A10.025
                                10.025 0 0 0 22 12.021C22 6.484 17.523 2 12 2z"/>
                        </svg>
                        GitHub
                    </a>
                </div>
            </div>

            <div class="text-right">
                <div class="text-xs text-gray-400">Laravel: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ \Illuminate\Foundation\Application::VERSION }}</span></div>
                <div class="text-xs text-gray-400">Filament: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ \Composer\InstalledVersions::getPrettyVersion('filament/filament') }}</span></div>
                <div class="text-xs text-gray-400">PHP: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ PHP_VERSION }}</span></div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
