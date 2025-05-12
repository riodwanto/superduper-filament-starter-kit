<div>
    @if(isset($schemaData))
        @push('js')
            <script type="application/ld+json">
                {!! $schemaData !!}
            </script>
        @endpush
    @endif

    {{-- Breadcrumb --}}
    <x-superduper.components.breadcrumb
        title="{{ $post->title }}"
        :items="[
            ['label' => 'Blog', 'url' => route('blog')],
            ['label' => $post->category->name, 'url' => route('blog', ['category' => $post->category->id])],
            ['label' => $post->title]
        ]"
    />

    {{-- Loader --}}
    <div wire:loading.delay class="fixed z-50 flex items-center gap-3 px-4 py-3 bg-white rounded-lg shadow-lg bottom-4 right-4">
        <svg class="w-5 h-5 text-primary-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    {{-- Blog Content --}}
    <div class="blog-section">
        <div class="section-space">
            <div class="container-default">
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 lg:grid-cols-[1fr,minmax(416px,_0.45fr)]">
                    <div class="flex flex-col gap-y-10 lg:gap-y-14 xl:gap-y-20">
                        <div class="flex flex-col gap-6">
                            <article class="overflow-hidden bg-white jos">
                                {{-- Featured Image --}}
                                <div class="mb-7 block overflow-hidden rounded-[10px]">
                                    @if($post->hasFeaturedImage())
                                        <img src="{{ $post->getFeaturedImageUrl('large') }}"
                                             alt="{{ $post->title }}"
                                             width="856" height="540"
                                             class="object-cover w-full h-auto scale-100" />
                                    @else
                                        <img src="https://placehold.co/856x540?text={{ urlencode($post->title) }}"
                                             alt="{{ $post->title }}"
                                             width="856" height="540"
                                             class="object-cover w-full h-auto scale-100" />
                                    @endif
                                </div>

                                <div class="px-[30px]">
                                    <!-- Blog Post Meta -->
                                    <ul class="mb-[30px] flex flex-wrap items-center gap-4 text-base font-semibold sm:gap-6">
                                        {{-- Author --}}
                                        <li>
                                            <div class="flex items-center gap-x-[10px]">
                                                @if($post->author && $post->author->profile_photo_path)
                                                    <img src="{{ Storage::url($post->author->profile_photo_path) }}"
                                                         alt="{{ $post->author->name }}"
                                                         width="45" height="45"
                                                         class="rounded-[50%]" />
                                                @else
                                                    <img src="https://placehold.co/45x45?text={{ substr($post->author->name ?? 'A', 0, 1) }}"
                                                         alt="{{ $post->author->name ?? 'Author' }}"
                                                         width="45" height="45"
                                                         class="rounded-[50%]" />
                                                @endif
                                                By {{ $post->author->name ?? 'Anonymous' }}
                                            </div>
                                        </li>

                                        {{-- Date --}}
                                        <li>
                                            <div class="flex items-center gap-x-[10px]">
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ $post->published_at->format('M d, Y') }}
                                            </div>
                                        </li>

                                        {{-- Category --}}
                                        <li>
                                            <a href="{{ route('blog', ['category' => $post->category->id]) }}"
                                               class="rounded-[50px] bg-color-black/5 px-[26px] py-1.5 text-black/60 hover:bg-color-blue hover:text-white">
                                                {{ $post->category->name }}
                                            </a>
                                        </li>

                                        {{-- Featured Badge --}}
                                        @if($post->is_featured)
                                            <li>
                                                <span class="rounded-[50px] bg-orange-500 px-[26px] py-1.5 text-white">
                                                    Featured
                                                </span>
                                            </li>
                                        @endif

                                        {{-- Reading Time --}}
                                        <li>
                                            <div class="flex items-center gap-x-[10px] text-gray-600">
                                                <i class="fa-regular fa-clock"></i>
                                                {{ $post->reading_time }} min read
                                            </div>
                                        </li>
                                    </ul>

                                    <!-- Blog Details Content -->
                                    <div>
                                        {{-- Post Title --}}
                                        <h1 class="mb-5 mt-8 font-body text-3xl font-bold leading-[1.4] -tracking-[1px] lg:text-4xl">
                                            {{ $post->title }}
                                        </h1>

                                        {{-- Post Content --}}
                                        <div class="prose prose-lg blog-content max-w-none">
                                            {!! $post->content_html !!}
                                        </div>

                                        <!-- Tags -->
                                        @if($post->tags && $post->tags->count() > 0)
                                            <div class="flex flex-wrap gap-2 mt-10">
                                                @foreach($post->tags as $tag)
                                                    <a href="{{ route('blog', ['search' => $tag->name]) }}"
                                                       class="rounded-[50px] bg-color-black/5 px-5 py-1.5 text-sm text-black/60 hover:bg-color-blue hover:text-white">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>

                            <!-- Horizontal Separator -->
                            <div class="jos my-[30px] h-[1px] w-full bg-[#EAEDF0]"></div>

                            <!-- Author Box -->
                            @if($post->author)
                                <div class="jos bg-color-off-white rounded-[10px] p-8">
                                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                                        <div class="flex-shrink-0 w-20 h-20">
                                            @if($post->author->profile_photo_path)
                                                <img src="{{ Storage::url($post->author->profile_photo_path) }}"
                                                     alt="{{ $post->author->name }}"
                                                     class="object-cover w-full h-full rounded-full" />
                                            @else
                                                <div class="flex items-center justify-center w-full h-full text-2xl text-white rounded-full bg-color-blue">
                                                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="mb-2 text-xl font-semibold">{{ $post->author->name }}</h3>
                                            <p class="mb-4 text-gray-600">{{ $post->author->description ?? 'Author at ' . config('app.name') }}</p>
                                            <div class="flex gap-3">
                                                <!-- Social media links if available -->
                                                @if($post->author->social_links && is_array($post->author->social_links))
                                                    @foreach($post->author->social_links as $platform => $url)
                                                        <a href="{{ $url }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-primary-600">
                                                            <i class="fa-brands fa-{{ $platform }}"></i>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Previous/Next Post Navigation -->
                            <div class="grid grid-cols-1 gap-4 jos md:grid-cols-2">
                                @if($previousPost)
                                    <a href="{{ $previousPost->getUrl() }}" class="flex flex-col p-6 bg-white rounded-lg shadow-sm group hover:shadow">
                                        <span class="flex items-center mb-2 text-sm text-gray-500">
                                            <i class="mr-2 fa-solid fa-arrow-left"></i> Previous Post
                                        </span>
                                        <h4 class="font-semibold text-black group-hover:text-primary-600 line-clamp-2">
                                            {{ $previousPost->title }}
                                        </h4>
                                    </a>
                                @endif

                                @if($nextPost)
                                    <a href="{{ $nextPost->getUrl() }}" class="flex flex-col p-6 text-right bg-white rounded-lg shadow-sm group hover:shadow">
                                        <span class="flex items-center justify-end mb-2 text-sm text-gray-500">
                                            Next Post <i class="ml-2 fa-solid fa-arrow-right"></i>
                                        </span>
                                        <h4 class="font-semibold text-black group-hover:text-primary-600 line-clamp-2">
                                            {{ $nextPost->title }}
                                        </h4>
                                    </a>
                                @endif
                            </div>

                            <!-- Related Posts -->
                            @if(count($relatedPosts) > 0)
                                <div class="mt-10 jos">
                                    <h3 class="relative mb-[30px] inline-block pb-[10px] text-xl font-semibold text-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                        Related Posts
                                    </h3>

                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                        @foreach($relatedPosts as $related)
                                            <div class="bg-white rounded-[10px] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                                <a href="{{ $related->getUrl() }}" class="block overflow-hidden">
                                                    @if($related->hasFeaturedImage())
                                                        <img src="{{ $related->getFeaturedImageUrl('medium') }}"
                                                             alt="{{ $related->title }}"
                                                             class="object-cover w-full h-48 transition-transform hover:scale-105" />
                                                    @else
                                                        <div class="flex items-center justify-center w-full h-48 bg-gray-200">
                                                            <span class="text-gray-400">No Image</span>
                                                        </div>
                                                    @endif
                                                </a>
                                                <div class="p-4">
                                                    <div class="mb-2 text-sm text-gray-500">
                                                        {{ $related->published_at->format('M d, Y') }}
                                                    </div>
                                                    <h4 class="mb-2 font-semibold text-black hover:text-primary-600 line-clamp-2">
                                                        <a href="{{ $related->getUrl() }}">{{ $related->title }}</a>
                                                    </h4>
                                                    <p class="mb-3 text-sm text-gray-600 line-clamp-2">
                                                        {{ $related->content_overview }}
                                                    </p>
                                                    <a href="{{ $related->getUrl() }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:underline">
                                                        Read More <i class="ml-1 fa-solid fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- Sidebar -->
                    <aside class="lg:sticky lg:top-8 lg:self-start flex flex-col gap-y-[30px]">
                        <!-- Recent Posts Widget -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Recent Posts
                            </div>

                            <ul class="flex flex-col gap-y-6">
                                @foreach($recentPosts as $recentPost)
                                    <li class="flex flex-col items-center group gap-x-4 gap-y-4 sm:flex-row">
                                        <a href="{{ $recentPost->getUrl() }}" class="inline-block h-[100px] w-full overflow-hidden rounded-[5px] sm:w-[150px]">
                                            @if($recentPost->hasFeaturedImage())
                                                <img src="{{ $recentPost->getFeaturedImageUrl('thumbnail') }}"
                                                     alt="{{ $recentPost->title }}"
                                                     width="150" height="100"
                                                     class="object-cover w-full h-full transition-all duration-300 scale-100 group-hover:scale-105" />
                                            @else
                                                <img src="https://placehold.co/150x100?text={{ substr($recentPost->title, 0, 10) }}"
                                                     alt="{{ $recentPost->title }}"
                                                     width="150" height="100"
                                                     class="object-cover w-full h-full" />
                                            @endif
                                        </a>
                                        <div class="flex flex-col w-full gap-y-3 sm:w-auto sm:flex-1">
                                            <div class="flex items-center gap-[10px] text-sm">
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ $recentPost->published_at->format('M d, Y') }}
                                            </div>
                                            <a href="{{ $recentPost->getUrl() }}" class="text-base font-semibold text-black line-clamp-2 hover:text-primary-600">
                                                {{ $recentPost->title }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Categories Widget -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Categories
                            </div>

                            <ul class="text-black">
                                @foreach($categories as $category)
                                    <li class="border-b border-color-black/10 pb-[14px] pt-[14px] first:pt-0 last:border-b-0 last:pb-0">
                                        <a href="{{ route('blog', ['category' => $category->id]) }}"
                                           class="flex items-center justify-between hover:text-primary-600">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-sm rounded-full bg-gray-200 px-2 py-0.5">
                                                {{ $category->posts_count }}
                                            </span>
                                        </a>
                                        </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tags Cloud Widget -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Tags
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('blog', ['search' => is_array($tag) ? $tag['name'] : $tag->name]) }}"
                                       class="inline-flex rounded-[50px] bg-color-black/5 px-4 py-1.5 text-sm hover:bg-color-blue hover:text-white">
                                        @if(is_array($tag))
                                            {{ $tag['name'] }}
                                        @elseif(is_object($tag) && is_string($tag->name))
                                            {{ $tag->name }}
                                        @elseif(is_object($tag) && is_object($tag->name))
                                            {{ $tag->name->{app()->getLocale()} ?? '' }}
                                        @else
                                            {{ is_string($tag) ? $tag : json_encode($tag) }}
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Share Options -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Share This Post
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button
                                    wire:click="sharePost('twitter')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#1DA1F2] text-white rounded-md hover:bg-opacity-90"
                                >
                                    <i class="fa-brands fa-twitter"></i>
                                    <span>Twitter</span>
                                </button>

                                <button
                                    wire:click="sharePost('facebook')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#1877F2] text-white rounded-md hover:bg-opacity-90"
                                >
                                    <i class="fa-brands fa-facebook"></i>
                                    <span>Facebook</span>
                                </button>

                                <button
                                    wire:click="sharePost('linkedin')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#0A66C2] text-white rounded-md hover:bg-opacity-90"
                                >
                                    <i class="fa-brands fa-linkedin"></i>
                                    <span>LinkedIn</span>
                                </button>

                                <button
                                    wire:click="sharePost('whatsapp')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#25D366] text-white rounded-md hover:bg-opacity-90"
                                >
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    /* Blog Content Styling */
    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4 {
        margin-top: 1.5em;
        margin-bottom: 0.5em;
        font-weight: 600;
        line-height: 1.3;
        color: #111827;
        scroll-margin-top: 80px;
    }

    .blog-content h1 {
        font-size: 2rem;
    }

    .blog-content h2 {
        font-size: 1.75rem;
    }

    .blog-content h3 {
        font-size: 1.5rem;
    }

    .blog-content h4 {
        font-size: 1.25rem;
    }

    .blog-content p {
        margin-bottom: 1.25em;
        line-height: 1.7;
    }

    .blog-content ul,
    .blog-content ol {
        margin-bottom: 1.25em;
        padding-left: 1.5em;
    }

    .blog-content ul {
        list-style-type: disc;
    }

    .blog-content ol {
        list-style-type: decimal;
    }

    .blog-content li {
        margin-bottom: 0.5em;
    }

    .blog-content img {
        border-radius: 0.5rem;
        margin: 1.5em 0;
    }

    .blog-content a {
        color: #3B82F6;
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 2px;
        transition: color 0.2s;
    }

    .blog-content a:hover {
        color: #2563EB;
        text-decoration-thickness: 2px;
    }

    .blog-content blockquote {
        margin: 1.5em 0;
        padding: 1em 1.5em;
        border-left: 4px solid #3B82F6;
        background-color: rgba(59, 130, 246, 0.05);
        border-radius: 0 0.5rem 0.5rem 0;
        font-style: italic;
        color: #4B5563;
    }

    .blog-content code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.9em;
        background-color: #F3F4F6;
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
    }

    .blog-content pre {
        margin: 1.5em 0;
        padding: 1em;
        background-color: #1F2937;
        border-radius: 0.5rem;
        overflow-x: auto;
    }

    .blog-content pre code {
        background-color: transparent;
        padding: 0;
        color: #E5E7EB;
        font-size: 0.9em;
        line-height: 1.5;
    }

    .blog-content table {
        width: 100%;
        margin: 1.5em 0;
        border-collapse: collapse;
    }

    .blog-content table th,
    .blog-content table td {
        padding: 0.75em;
        border: 1px solid #E5E7EB;
    }

    .blog-content table th {
        background-color: #F9FAFB;
        font-weight: 600;
    }

    .blog-content table tr:nth-child(even) {
        background-color: #F9FAFB;
    }

    .jos {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .jos.active {
        opacity: 1;
        transform: translateY(0);
    }

    [wire\:loading].fixed {
        animation: fadeIn 0.3s ease-in-out;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .lg\:sticky {
        scrollbar-width: thin;
        padding-top: 72px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
</style>
@endpush
