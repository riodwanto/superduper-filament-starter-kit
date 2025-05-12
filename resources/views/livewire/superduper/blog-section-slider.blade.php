<div class="section-articles">
    <div class="jos">
        <!-- Section Space -->
        <div class="horizontal-line bg-primary-800"></div>
        <div class="py-[60px] md:py-20 lg:py-[100px]">
            <!-- Section Container -->
            <div class="container-default">
                <!-- Section Title -->
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-medium font-ClashDisplay md:text-4xl lg:text-6xl text-primary-800">
                        Latest Articles
                    </h2>
                    <p class="max-w-3xl mx-auto text-primary-700">
                        Discover tips, tutorials, and insights about building powerful applications with Laravel and Filament.
                    </p>
                </div>

                <!-- Category Filters -->
                @if(count($categories) > 0)
                <div class="flex flex-wrap items-center justify-center gap-3 mb-8">
                    <button
                        wire:click="filterByCategory(null)"
                        class="px-4 py-2 text-sm font-medium transition-colors {{ is_null($activeCategory) ? 'bg-primary-600 text-white' : 'bg-background-light text-primary-800 hover:bg-background-subtle' }}"
                    >
                        All
                    </button>

                    @foreach($categories as $category)
                        <button
                            wire:click="filterByCategory('{{ $category->id }}')"
                            class="px-4 py-2 text-sm font-medium transition-colors {{ $activeCategory === $category->id ? 'bg-primary-600 text-white' : 'bg-background-light text-primary-800 hover:bg-background-subtle' }}"
                        >
                            {{ $category->name }}
                        </button>
                    @endforeach

                    <button
                        wire:click="toggleFeatured"
                        class="px-4 py-2 text-sm font-medium transition-colors {{ $featuredOnly ? 'bg-secondary-600 text-white' : 'bg-background-light text-primary-800 hover:bg-background-subtle' }}"
                    >
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            Featured
                        </span>
                    </button>
                </div>
                @endif

                <!-- Articles Slider Container -->
                <div class="relative" wire:loading.class="opacity-50 pointer-events-none">
                    <!-- Loading Indicator -->
                    <div wire:loading class="absolute inset-0 z-50 flex items-center justify-center bg-opacity-60">
                        <svg class="w-10 h-10 text-primary-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <button id="article-slider-prev" class="absolute left-0 z-20 flex items-center justify-center w-12 h-12 text-white transition-all duration-300 -translate-x-6 -translate-y-1/2 shadow-lg cursor-pointer bg-primary-700 articles-slider-prev top-1/2 hover:scale-110 hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button id="article-slider-next" class="absolute right-0 z-20 flex items-center justify-center w-12 h-12 text-white transition-all duration-300 translate-x-6 -translate-y-1/2 shadow-lg cursor-pointer bg-primary-700 articles-slider-next top-1/2 hover:scale-110 hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Articles Slider -->
                    <div class="px-1 py-2 overflow-visible swiper articles-slider">
                        <div class="swiper-wrapper">
                            @forelse($articles as $article)
                                <!-- Article Slide -->
                                <div class="swiper-slide">
                                    <div class="flex flex-col h-full overflow-hidden transition-all duration-300 bg-white border shadow-lg border-background-light group hover:shadow-xl">
                                        <a href="{{ route('blog.show', ['slug' => $article->slug]) }}" class="flex-shrink-0 block" wire:click="trackView('{{ $article->id }}')">
                                            <div class="relative overflow-hidden aspect-video">
                                                @if($article->hasFeaturedImage())
                                                    <img src="{{ $article->getFeaturedImageUrl('large') }}" alt="{{ $article->title }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                                                @else
                                                    <img src="https://placehold.co/800x450?text={{ urlencode($article->title) }}" alt="{{ $article->title }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                                                @endif
                                                <div class="absolute inset-0 transition-opacity bg-gradient-to-t from-primary-900/60 via-primary-900/20 to-transparent opacity-60 group-hover:opacity-70"></div>
                                                <div class="absolute bottom-0 left-0 right-0 p-5">
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold tracking-wider text-white uppercase shadow-md"
                                                        style="background-color: {{ $article->category->options['color'] ?? '#2D2B8D' }}">
                                                        {{ $article->category->name }}
                                                    </span>
                                                    @if($article->is_featured)
                                                        <span class="inline-block px-3 py-1 ml-2 text-xs font-semibold tracking-wider text-white uppercase shadow-md bg-secondary-600">
                                                            Featured
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                        <div class="flex flex-col flex-grow p-6">
                                            <div class="flex items-center mb-3 text-sm text-neutral-500">
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $article->published_at->format('M d, Y') }}
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $article->reading_time }} min read
                                                </span>
                                            </div>
                                            <h3 class="mb-3 text-xl font-bold transition-colors text-primary-800 group-hover:text-primary-600">
                                                <a href="{{ route('blog.show', ['slug' => $article->slug]) }}" wire:click="trackView('{{ $article->id }}')">
                                                    {{ $article->title }}
                                                </a>
                                            </h3>
                                            <p class="flex-grow mb-5 text-neutral-600">{{ $article->content_overview }}</p>
                                            <div class="flex items-center justify-between pt-4 mt-auto border-t border-background-light">
                                                <div class="flex items-center">
                                                    @if($article->author && $article->author->profile_photo_path)
                                                        <img src="{{ Storage::url($article->author->profile_photo_path) }}" alt="{{ $article->author->name }}" class="mr-3 border-2 border-white shadow-sm w-9 h-9">
                                                    @else
                                                        <img src="https://placehold.co/40x40?text={{ substr($article->author->name ?? 'A', 0, 2) }}" alt="{{ $article->author->name ?? 'Author' }}" class="mr-3 border-2 border-white shadow-sm w-9 h-9">
                                                    @endif
                                                    <span class="text-sm font-medium text-primary-700">{{ $article->author->name ?? 'Anonymous' }}</span>
                                                </div>
                                                <a href="{{ route('blog.show', ['slug' => $article->slug]) }}" wire:click="trackView('{{ $article->id }}')" class="flex items-center text-sm font-medium transition-colors text-primary-600 hover:text-primary-700">
                                                    Read More
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="w-full p-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <p class="text-lg font-medium text-neutral-500">No articles found</p>
                                        <p class="text-neutral-400">Try changing your filter criteria</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- End Articles Slider Container -->

                <!-- Pagination -->
                <div class="mt-8 text-center">
                    <div class="articles-slider-pagination"></div>
                </div>

                <!-- View All Articles Button -->
                <div class="mt-12 text-center">
                    <a href="{{ route('blog') }}" class="inline-block px-6 py-3 font-medium transition-all duration-300 border-2 text-primary-700 border-primary-600 hover:bg-primary-600 hover:text-white group">
                        <span class="flex items-center">
                            View All Articles
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <!-- Section Container -->
        </div>
        <!-- Section Space -->
    </div>
</div>

@push('css')
<style>
.articles-slider-pagination {
    --swiper-pagination-color: #2D2B8D; /* Primary-800 */
    --swiper-pagination-bullet-size: 10px;
    --swiper-pagination-bullet-inactive-color: #DCDCF7; /* Primary-100 */
    --swiper-pagination-bullet-inactive-opacity: 1;
    --swiper-pagination-bullet-horizontal-gap: 6px;
}

.articles-slider-prev,
.articles-slider-next {
    pointer-events: auto !important;
    opacity: 1 !important;
}

.articles-slider {
    overflow: visible !important;
}

.swiper-wrapper {
    display: flex !important;
    gap: 24px !important;
}

.articles-slider .swiper-slide {
    flex-shrink: 0 !important;
    margin-right: 0 !important;
    transition: none !important;
}

@media (min-width: 1024px) {
    .articles-slider .swiper-slide {
        width: calc((100% - 48px) / 3) !important;
    }
}

@media (min-width: 640px) and (max-width: 1023px) {
    .articles-slider .swiper-slide {
        width: calc((100% - 24px) / 2) !important;
    }
}

@media (max-width: 768px) {
    .articles-slider-prev {
        left: 10px !important;
        transform: translateY(-50%) !important;
        translate: 0 !important;
    }

    .articles-slider-next {
        right: 10px !important;
        transform: translateY(-50%) !important;
        translate: 0 !important;
    }
}
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initArticleSlider();
    });

    document.addEventListener('livewire:load', function() {
        initArticleSlider();

        Livewire.hook('message.processed', (message, component) => {
            setTimeout(() => {
                if (window.articlesSlider) {
                    const currentIndex = window.articlesSlider.activeIndex;

                    window.articlesSlider.destroy(true, true);
                    window.articlesSlider = null;

                    initArticleSlider(currentIndex);
                } else {
                    initArticleSlider();
                }
            }, 200);
        });
    });

    function initArticleSlider(initialSlide = 0) {
        if (typeof Swiper !== 'undefined' && document.querySelector('.articles-slider')) {
            document.querySelectorAll('.articles-slider .swiper-slide').forEach(slide => {
                slide.style.width = '';
                slide.style.marginRight = '';
            });

            const spaceBetween = 24;

            window.articlesSlider = new Swiper('.articles-slider', {
                slidesPerView: 1,
                spaceBetween: spaceBetween,
                loop: false,
                grabCursor: true,
                initialSlide: initialSlide,
                pagination: {
                    el: '.articles-slider-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '#article-slider-next',
                    prevEl: '#article-slider-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: spaceBetween,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: spaceBetween,
                    },
                },
                on: {
                    init: function() {
                        ensureCorrectSpacing(this, spaceBetween);
                    },
                    resize: function() {
                        ensureCorrectSpacing(this, spaceBetween);
                    }
                }
            });
        }
    }

    function ensureCorrectSpacing(swiper, spaceBetween) {
        if (!swiper || !swiper.slides || !swiper.slides.length) return;

        swiper.slides.forEach((slide, index) => {
            if (index < swiper.slides.length - 1) {
                slide.style.marginRight = `${spaceBetween}px`;
            }
        });

        swiper.update();
    }
</script>
@endpush
