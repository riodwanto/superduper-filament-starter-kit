<footer class="flex items-center justify-between w-full px-4 py-8 font-medium bg-transparent dark:bg-gray-900 dark:border-gray-700">
    <span class="text-sm text-center text-gray-300 dark:text-gray-200">
        <a href="#" class="hover:underline">{{ config('app.name') }}</a> {{
            env('APP_VERSION') ? "v".env('APP_VERSION'): '' }}
    </span>
    <span class="text-sm text-center text-gray-300 dark:text-gray-200">©{{ date('Y') }} All Rights
        Reserved.
    </span>
</footer>
