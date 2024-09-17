<footer class="flex items-center justify-between w-full px-4 py-8 font-medium">
    <span class="text-sm text-center text-gray-400 dark:text-gray-300">
        <a href="#" class="hover:underline">{{ config('app.name') }}</a> {{
            env('APP_VERSION') ? "v".env('APP_VERSION'): '' }}
    </span>
    <span class="text-sm text-center text-gray-400 dark:text-gray-300">©{{ date('Y') }} All Rights
        Reserved.
    </span>
</footer>
