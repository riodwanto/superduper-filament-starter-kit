<div class="space-y-6">
    <!-- Google Search Preview -->
    <div>
        <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">Google Search Preview</h3>
        <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            <div class="space-y-1">
                <div class="text-xl text-blue-600 cursor-pointer dark:text-blue-400 hover:underline">
                    {{ Str::limit($title, 60) }}
                </div>
                <div class="text-sm text-green-700 dark:text-green-400">
                    {{ $url }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ Str::limit($description, 160) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Facebook/Open Graph Preview -->
    <div>
        <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">Facebook Preview</h3>
        <div class="max-w-md overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            @if($featuredImage)
                <img src="{{ $featuredImage }}" alt="{{ $title }}" class="object-cover w-full h-48">
            @else
                <div class="flex items-center justify-center w-full h-48 bg-gray-200 dark:bg-gray-700">
                    <span class="text-gray-500 dark:text-gray-400">No featured image</span>
                </div>
            @endif
            <div class="p-3">
                <div class="mb-1 text-xs text-gray-500 uppercase dark:text-gray-400">
                    {{ parse_url($url, PHP_URL_HOST) }}
                </div>
                <div class="mb-1 font-semibold text-gray-900 dark:text-gray-100">
                    {{ Str::limit($title, 80) }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ Str::limit($description, 120) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Twitter Card Preview -->
    <div>
        <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">Twitter Card Preview</h3>
        <div class="max-w-md overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            @if($featuredImage)
                <img src="{{ $featuredImage }}" alt="{{ $title }}" class="object-cover w-full h-48">
            @else
                <div class="flex items-center justify-center w-full h-48 bg-gray-200 dark:bg-gray-700">
                    <span class="text-gray-500 dark:text-gray-400">No featured image</span>
                </div>
            @endif
            <div class="p-3">
                <div class="mb-1 font-semibold text-gray-900 dark:text-gray-100">
                    {{ Str::limit($title, 70) }}
                </div>
                <div class="mb-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ Str::limit($description, 125) }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ parse_url($url, PHP_URL_HOST) }}
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Tips -->
    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20">
        <h4 class="mb-2 font-medium text-blue-900 dark:text-blue-100">SEO Tips</h4>
        <ul class="space-y-1 text-sm text-blue-800 dark:text-blue-200">
            <li>• Title should be 50-60 characters (currently: {{ strlen($title) }})</li>
            <li>• Description should be 150-160 characters (currently: {{ strlen($description) }})</li>
            <li>• Featured image recommended: 1200x630px for best social sharing</li>
            @if(!$featuredImage)
                <li class="text-orange-600 dark:text-orange-400">⚠ No featured image set - social shares may not display properly</li>
            @endif
        </ul>
    </div>
</div>
