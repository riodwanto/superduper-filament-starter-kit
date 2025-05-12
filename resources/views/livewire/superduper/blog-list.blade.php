<div>
    <!-- Post List Loader -->
    <div wire:loading.delay wire:target="filterByCategory, search, toggleFeatured, sortBy, nextPage, previousPage, gotoPage"
         class="fixed z-50 flex items-center gap-3 px-4 py-3 bg-white rounded-lg shadow-lg bottom-4 right-4">
        <svg class="w-5 h-5 text-primary-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <x-superduper.components.breadcrumb
        title="{{ $activeCategory ? ($categories->firstWhere('id', $activeCategory)?->name . ' Articles') : 'Blog' }}"
        :items="$activeCategory ? [['label' => 'Blog', 'url' => route('blog')], ['label' => $categories->firstWhere('id', $activeCategory)?->name]] : []"
    />

    <section class="blog-section">
        <!-- Section Spacer -->
        <div class="section-space">
            <!-- Section Container -->
            <div class="container-default">
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 lg:grid-cols-[1fr,minmax(416px,_0.45fr)]">
                    <div class="flex flex-col gap-y-10 lg:gap-y-14 xl:gap-y-20">
                        <!-- Search Results Status and Sort Controls -->
                        <div class="flex flex-col items-start justify-between p-4 mb-6 bg-white rounded-lg shadow-sm sm:flex-row sm:items-center">
                            <!-- Search Status -->
                            <div class="mb-3 sm:mb-0">
                                @if($search)
                                    <div class="text-gray-700">
                                        <span class="font-medium">Search results for:</span>
                                        <span class="px-2 py-1 rounded bg-color-blue/10 text-primary-600">{{ $search }}</span>

                                        @if($posts->total() > 0)
                                            <span class="ml-2 text-gray-600">
                                                ({{ $posts->total() }} {{ Str::plural('result', $posts->total()) }})
                                            </span>
                                        @else
                                            <span class="ml-2 text-gray-600">
                                                (No results found)
                                            </span>
                                        @endif

                                        <button wire:click="clearSearch" class="ml-2 text-primary-600 hover:underline">
                                            Clear
                                        </button>
                                    </div>
                                @elseif($activeCategory)
                                    <div class="text-gray-700">
                                        <span class="font-medium">Category:</span>
                                        <span class="px-2 py-1 rounded bg-color-blue/10 text-primary-600">
                                            {{ $categories->firstWhere('id', $activeCategory)?->name ?? 'Selected Category' }}
                                        </span>

                                        <button wire:click="filterByCategory(null)" class="ml-2 text-primary-600 hover:underline">
                                            Clear
                                        </button>
                                    </div>
                                @elseif($featuredOnly)
                                    <div class="text-gray-700">
                                        <span class="font-medium">Showing:</span>
                                        <span class="px-2 py-1 text-orange-600 bg-orange-100 rounded">
                                            Featured Posts
                                        </span>

                                        <button wire:click="toggleFeatured" class="ml-2 text-primary-600 hover:underline">
                                            Show All
                                        </button>
                                    </div>
                                @else
                                    <h2 class="font-medium text-gray-700">All Posts</h2>
                                @endif
                            </div>

                            <!-- Sort Controls -->
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Sort by:</span>
                                <div class="flex overflow-hidden border rounded">
                                    <button
                                        wire:click="sortBy('published_at')"
                                        class="flex items-center px-3 py-1.5 text-sm {{ $sortField === 'published_at' ? 'bg-color-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}"
                                    >
                                        Date
                                        @if($sortField === 'published_at')
                                            <i class="fa-solid fa-chevron-{{ $sortDirection === 'desc' ? 'down' : 'up' }} ml-1 text-xs"></i>
                                        @endif
                                    </button>
                                    <button
                                        wire:click="sortBy('view_count')"
                                        class="flex items-center px-3 py-1.5 text-sm border-l {{ $sortField === 'view_count' ? 'bg-color-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}"
                                    >
                                        Popular
                                        @if($sortField === 'view_count')
                                            <i class="fa-solid fa-chevron-{{ $sortDirection === 'desc' ? 'down' : 'up' }} ml-1 text-xs"></i>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Blog Post List -->
                        <div class="relative grid gap-y-10">
                            <div wire:loading.delay wire:target="filterByCategory, search, toggleFeatured, sortBy, nextPage, previousPage, gotoPage"
                                 class="absolute inset-0 z-10 bg-white rounded-lg bg-opacity-40">
                            </div>

                            @forelse($posts as $post)
                                <!-- Blog Post Single Item -->
                                <div class="jos" wire:key="post-{{ $post->id }}">
                                    <div class="group overflow-hidden rounded-[10px] border border-[#E1E1E] bg-white hover:border-white hover:shadow-[0_4px_60px_rgba(10,16,47,0.08)]">
                                        <a href="{{ $post->getUrl() }}"  class="block overflow-hidden" wire:click="trackView('{{ $post->id }}')">
                                            @if($post->hasFeaturedImage())
                                                <img src="{{ $post->getFeaturedImageUrl('large') }}" alt="{{ $post->title }}" width="856" height="450" class="object-cover w-full h-auto transition-all duration-300 scale-100 group-hover:scale-105" />
                                            @else
                                                <img src="https://placehold.co/856x450?text={{ urlencode($post->title) }}" alt="{{ $post->title }}" width="856" height="450" class="object-cover w-full h-auto transition-all duration-300 scale-100 group-hover:scale-105" />
                                            @endif
                                        </a>
                                        <div class="p-[30px]">
                                            <!-- Blog Post Meta -->
                                            <ul class="flex flex-wrap items-center gap-4 text-base font-semibold sm:gap-6">
                                                <li>
                                                    <a href="{{ $post->getUrl() }}"  class="flex items-center gap-x-[10px] hover:text-primary-600" wire:click="trackView('{{ $post->id }}')">
                                                        @if($post->author && $post->author->profile_photo_path)
                                                            <img src="{{ Storage::url($post->author->profile_photo_path) }}" alt="{{ $post->author->name }}" width="45" height="45" class="rounded-[50%]" />
                                                        @else
                                                            <img src="https://placehold.co/45x45?text={{ substr($post->author->name ?? 'A', 0, 1) }}" alt="{{ $post->author->name ?? 'Author' }}" width="45" height="45" class="rounded-[50%]" />
                                                        @endif
                                                        By {{ $post->author->name ?? 'Anonymous' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ $post->getUrl() }}"  class="flex items-center gap-x-[10px] hover:text-primary-600" wire:click="trackView('{{ $post->id }}')">
                                                        <i class="fa-regular fa-calendar"></i>
                                                        {{ $post->published_at->format('M d, Y') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="filterByCategory('{{ $post->category->id }}')" class="rounded-[50px] bg-color-black/5 px-[26px] py-1.5 text-color-black/60 hover:bg-color-blue hover:text-white">
                                                        {{ $post->category->name }}
                                                    </a>
                                                </li>
                                                @if($post->is_featured)
                                                    <li>
                                                        <span class="rounded-[50px] bg-orange-500 px-[26px] py-1.5 text-white">
                                                            Featured
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                            <!-- Blog Post Meta -->
                                            <h2 class="mb-5 mt-8 line-clamp-2 font-body text-2xl font-bold leading-[1.4] -tracking-[1px] lg:text-3xl">
                                                <a href="{{ $post->getUrl() }}"  wire:click="trackView('{{ $post->id }}')">
                                                    {{ $post->title }}
                                                </a>
                                            </h2>
                                            <p class="mb-7 line-clamp-2 last:mb-0">
                                                {{ $post->content_overview }}
                                            </p>
                                            <a href="{{ $post->getUrl() }}"  wire:click="trackView('{{ $post->id }}')" class="inline-flex items-center text-base font-bold gap-x-2 text-color-black group-hover:text-primary-600">
                                                Read More
                                                <span class="transition-all duration-300 ease-in-out group-hover:translate-x-2">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- No Posts Found Message -->
                                <div class="w-full p-10 text-center bg-white rounded-[10px] shadow-sm">
                                    <div class="flex flex-col items-center justify-center py-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">No articles found</p>
                                        <p class="mb-6 text-gray-400">Try changing your search criteria</p>
                                        <button wire:click="filterByCategory(null)" class="inline-block rounded-[50px] bg-color-blue px-6 py-3 text-white hover:bg-blue-700 transition">
                                            View All Posts
                                        </button>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($posts->hasPages())
                            <nav aria-label="Pagination" class="flex justify-center">
                                <ul class="flex gap-x-[15px]">
                                    <!-- Previous Page -->
                                    <li>
                                        <button wire:click="previousPage" @if(!$posts->onFirstPage()) wire:loading.attr="disabled" @endif @if($posts->onFirstPage()) disabled @endif class="group flex h-10 w-10 items-center justify-center rounded-[50%] {{ $posts->onFirstPage() ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-color-blue hover:text-white' }} font-semibold transition-all duration-300 lg:h-[50px] lg:w-[50px]">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </button>
                                    </li>

                                    <!-- Page Numbers -->
                                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        <li>
                                            <button wire:click="gotoPage({{ $page }})" class="group flex h-10 w-10 items-center justify-center rounded-[50%] {{ $page == $posts->currentPage() ? 'bg-color-blue text-white' : 'bg-white hover:bg-color-blue hover:text-white' }} font-semibold transition-all duration-300 lg:h-[50px] lg:w-[50px]">
                                                {{ $page }}
                                            </button>
                                        </li>
                                    @endforeach

                                    <!-- Next Page -->
                                    <li>
                                        <button wire:click="nextPage" @if(!$posts->hasMorePages()) wire:loading.attr="disabled" @endif @if(!$posts->hasMorePages()) disabled @endif class="group flex h-10 w-10 items-center justify-center rounded-[50%] {{ !$posts->hasMorePages() ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-color-blue hover:text-white' }} font-semibold transition-all duration-300 lg:h-[50px] lg:w-[50px]">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>

                    <aside class="lg:sticky lg:top-8 lg:self-start flex flex-col gap-y-[30px]">
                        <!-- Search -->
                        <div class="rounded-[10px] bg-color-off-white p-5">
                            <h3 id="search-heading" class="sr-only">Search Blog</h3>

                            <form class="relative h-[60px]" wire:submit.prevent="$refresh" role="search" aria-labelledby="search-heading">
                                <label for="blog-search-input" class="sr-only">Search blog posts</label>
                                <input
                                    id="blog-search-input"
                                    type="search"
                                    wire:model.debounce.500ms="search"
                                    placeholder="Type to search..."
                                    class="h-full w-full rounded-[50px] border border-[#E1E1E1] bg-white py-[15px] pl-16 pr-12 text-lg text-color-black outline-none transition-all placeholder:text-color-black focus:border-color-blue focus:ring-2 focus:ring-color-blue/20"
                                    aria-describedby="search-description"
                                />
                                <div id="search-description" class="sr-only">
                                    Search for blog posts by title, content, or tags
                                </div>

                                <div class="absolute left-[30px] top-0 h-full flex items-center text-gray-500" aria-hidden="true">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>

                                <div class="absolute right-[20px] top-0 h-full flex items-center">
                                    @if($search)
                                        <button
                                            wire:click="clearSearch"
                                            type="button"
                                            class="p-1 text-gray-400 transition-colors rounded-full hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-color-blue/40"
                                            aria-label="Clear search"
                                        >
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    @endif

                                    <div wire:loading wire:target="search" class="ml-2 text-primary-600" aria-live="polite">
                                        <span class="sr-only">Searching...</span>
                                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </form>

                            @if($search)
                                <div class="flex items-center justify-between mt-2 text-sm text-gray-600" aria-live="polite">
                                    <div>
                                        @if($posts->total() > 0)
                                            Found {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} for "{{ $search }}"
                                        @else
                                            No results found for "{{ $search }}"
                                        @endif
                                    </div>
                                    <button
                                        wire:click="clearSearch"
                                        class="px-2 rounded text-primary-600 hover:underline focus:outline-none focus:ring-2 focus:ring-color-blue/40"
                                    >
                                        Clear search
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Posts -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-color-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Recent Posts
                            </div>

                            <!-- Blog Recent Post List -->
                            <ul class="flex flex-col gap-y-6">
                                @foreach($recentPosts as $recentPost)
                                    <li class="flex flex-col items-center group gap-x-4 gap-y-4 sm:flex-row">
                                        <a href="{{ $recentPost->getUrl() }}" class="inline-block h-[100px] w-full overflow-hidden rounded-[5px] sm:w-[150px]" wire:click="trackView('{{ $recentPost->id }}')">
                                            @if($recentPost->hasFeaturedImage())
                                                <img src="{{ $recentPost->getFeaturedImageUrl('thumbnail') }}" alt="{{ $recentPost->title }}" width="150" height="100" class="object-cover w-full h-full transition-all duration-300 scale-100 group-hover:scale-105" />
                                            @else
                                                <img src="https://placehold.co/150x100?text={{ substr($recentPost->title, 0, 10) }}" alt="{{ $recentPost->title }}" width="150" height="100" class="object-cover w-full h-full transition-all duration-300 scale-100 group-hover:scale-105" />
                                            @endif
                                        </a>
                                        <div class="flex flex-col w-full gap-y-3 sm:w-auto sm:flex-1">
                                            <a href="{{ $recentPost->getUrl() }}" class="flex items-center gap-[10px] text-sm hover:text-primary-600" wire:click="trackView('{{ $recentPost->id }}')">
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ $recentPost->published_at->format('M d, Y') }}
                                            </a>
                                            <a href="{{ $recentPost->getUrl() }}" class="text-base font-semibold line-clamp-2 text-color-black hover:text-primary-600" wire:click="trackView('{{ $recentPost->id }}')">
                                                {{ $recentPost->title }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Blog Categories -->
                        <div class="rounded-[10px] bg-color-off-white p-8 relative">
                            <!-- Loading indicator for category filtering only -->
                            <div wire:loading.delay wire:target="filterByCategory" class="absolute text-primary-600 right-8 top-8">
                                <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-color-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Blog Categories
                            </div>
                            <!-- Blog Categories List -->
                            <ul class="text-color-black">
                                <li class="border-b border-color-black/10 pb-[14px] pt-[14px] first:pt-0 last:border-b-0 last:pb-0">
                                    <button wire:click="filterByCategory(null)" class="w-full text-left {{ is_null($activeCategory) ? 'text-primary-600 font-semibold' : 'hover:text-primary-600' }}">
                                        All Categories
                                    </button>
                                </li>
                                @foreach($categories as $category)
                                    <li class="border-b border-color-black/10 pb-[14px] pt-[14px] first:pt-0 last:border-b-0 last:pb-0">
                                        <button wire:click="filterByCategory('{{ $category->id }}')" class="w-full text-left {{ $activeCategory === $category->id ? 'text-primary-600 font-semibold' : 'hover:text-primary-600' }}">
                                            {{ $category->name }} ({{ $category->posts_count }})
                                        </button>
                                    </li>
                                @endforeach
                                <li class="border-b border-color-black/10 pb-[14px] pt-[14px] first:pt-0 last:border-b-0 last:pb-0">
                                    <button wire:click="toggleFeatured" class="w-full text-left flex items-center {{ $featuredOnly ? 'text-primary-600 font-semibold' : 'hover:text-primary-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                        Featured Only
                                        <span wire:loading.delay wire:target="toggleFeatured" class="ml-2 text-primary-600">
                                            <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tags -->
                        <div class="rounded-[10px] bg-color-off-white p-8">
                            <div class="relative mb-[30px] inline-block pb-[10px] text-lg font-semibold text-color-black after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-black">
                                Tags
                            </div>

                            <ul class="flex flex-wrap gap-x-2 gap-y-4">
                                @forelse($popularTags as $tag)
                                    <li>
                                        <button
                                            wire:click="searchByTag('{{ is_object($tag) ? $tag->name : $tag['name'] }}')"
                                            class="inline-flex items-center rounded-[55px] bg-color-black bg-opacity-5 px-5 py-1.5 hover:bg-color-blue hover:text-white transition"
                                        >
                                            <span>
                                                @if(is_object($tag) && isset($tag->name) && is_string($tag->name))
                                                    {{ $tag->name }}
                                                @elseif(is_object($tag) && isset($tag->name) && is_object($tag->name))
                                                    {{ $tag->name->{app()->getLocale()} ?? '' }}
                                                @elseif(is_array($tag) && isset($tag['name']) && is_string($tag['name']))
                                                    {{ $tag['name'] }}
                                                @elseif(is_array($tag) && isset($tag['name']) && is_array($tag['name']))
                                                    {{ $tag['name'][app()->getLocale()] ?? '' }}
                                                @else
                                                    {{ is_string($tag) ? $tag : json_encode($tag) }}
                                                @endif
                                            </span>
                                            <span class="ml-1.5 flex items-center justify-center text-xs h-5 w-5 rounded-full bg-gray-200 text-gray-700">
                                                {{ is_object($tag) ? ($tag->count ?? 1) : ($tag['count'] ?? 1) }}
                                            </span>
                                        </button>
                                    </li>
                                @empty
                                    <li class="italic text-gray-500">No tags found</li>
                                @endforelse
                            </ul>

                            @if($search && count($popularTags) > 0)
                                <div class="mt-4 text-sm text-gray-600">
                                    <button wire:click="clearSearch" class="flex items-center text-primary-600 hover:underline">
                                        <i class="mr-1 text-xs fa-solid fa-arrow-left"></i>
                                        View all tags
                                    </button>
                                </div>
                            @endif
                        </div>
                    </aside>
                    <!-- Blog Aside -->
                </div>
            </div>
            <!-- Section Container -->
        </div>
        <!-- Section Spacer -->
    </section>
</div>

@push('css')
<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

[wire\:loading].fixed {
    animation: fadeIn 0.3s ease-in-out;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

[wire\:loading] svg {
    display: inline-block;
}

[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (min-width: 1024px) {
    .lg\:sticky {
        position: sticky;
        scrollbar-width: thin;
        padding-top: 72px;
    }

    .lg\:sticky::-webkit-scrollbar {
        width: 4px;
    }

    .lg\:sticky::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .lg\:sticky::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .lg\:sticky::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }
}
</style>
@endpush
