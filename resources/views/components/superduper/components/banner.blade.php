@php
    $banners = \App\Models\Banner\Content::whereHas('category', function($query) {
            $query->where('slug', 'home-banner');
        })
        ->active()
        ->orderBy('sort')
        ->with(['media'])
        ->take(5)
        ->get();
@endphp

<section title="" class="w-full">
    <div class="relative z-10 overflow-hidden">

        @if($banners->isNotEmpty())

            <div class="h-[667px] swiper hero-slider">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        <div class="swiper-slide" data-banner-id="{{ $banner->id }}">

                            <div class="absolute top-0 left-0 w-full h-full mx-auto jos lg:mx-0" data-jos_animation="fade-right">
                                @if($banner->hasImage())
                                    <img src="{{ $banner->getImageUrl('large') }}"
                                            srcset="{{ $banner->getImageUrl('medium') }} 768w, {{ $banner->getImageUrl('large') }} 1200w"
                                            alt="{{ $banner->title }}"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                            class="object-cover object-center w-full h-full banner-image" />
                                @else
                                    <img src="https://placehold.co/200x527"
                                            alt="Placeholder Image"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                            class="object-cover object-center w-full h-full" />
                                @endif
                            </div>

                            <div class="flex items-center justify-start w-full h-full gap-10 c-container">
                                <div class="absolute top-0 left-0 w-full h-full mx-auto opacity-50 bg-gradient-to-r from-white via-white to-transparent jos lg:mx-0" data-jos_animation="fade-right"></div>

                                <div class="z-20 text-center jos xl:text-left" data-jos_animation="fade-left">
                                    <h1 class="max-w-lg mb-6 text-4xl font-bold leading-snug">
                                        {{ $banner->title }}
                                    </h1>
                                    <p class="max-w-md mb-8 text-base text-gray-800">
                                        {!! nl2br(e($banner->description)) !!}
                                    </p>

                                    @if($banner->click_url)
                                        <div class="flex flex-wrap justify-center gap-6 xl:justify-start">
                                            <a href="{{ $banner->click_url }}"
                                                target="{{ $banner->click_url_target ?? '_self' }}"
                                                class="px-10 py-2 text-base font-bold transition-all duration-300 ease-in-out transform bg-white border-2 rounded-full text-primary-600 border-primary-600 bg-gradient-to-r from-btn-gradient-secondary-start via-btn-gradient-secondary-middle to-btn-gradient-secondary-end hover:scale-105 hover:shadow-xl hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-4 focus:ring-primary-300"
                                                onclick="trackBannerClick('{{ $banner->id }}')">
                                                <span>{{ $banner->options['button_text'] ?? 'Contact Us' }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                @if($banners->count() > 1)
                    <div class="swiper-pagination hero-banner-slider-pagination"></div>
                @endif
            </div>

        @endif

    </div>
</section>

@push('js')
<script>
    const heroSlider = new Swiper('.hero-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: {{ $banners->count() > 1 ? 'true' : 'false' }},
        // autoplay: {
        //     delay: 2500,
        //     disableOnInteraction: false,
        // },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        navigation: false,
        pagination: {
            el: '.hero-banner-slider-pagination',
            clickable: true,
        },
        on: {
            init: function() {
                trackBannerView(this);
            },
            slideChange: function() {
                // trackBannerView(this);
            }
        }
    });

    function trackBannerView(swiper) {
        if (!swiper || !swiper.slides) return;

        const activeSlide = swiper.slides[swiper.activeIndex];
        if (!activeSlide || !activeSlide.dataset.bannerId) return;

        const bannerId = activeSlide.dataset.bannerId;

        fetch(`/api/banners/${bannerId}/impression`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            keepalive: true
        }).catch(() => {});
    }

    function trackBannerClick(bannerId) {
        fetch(`/api/banners/${bannerId}/click`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            keepalive: true
        }).catch(() => {});
    }
</script>
@endpush
