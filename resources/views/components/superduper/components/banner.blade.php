@php
    $heroBanners = \App\Models\Banner\Content::whereHas('category', function($query) {
            $query->where('slug', 'home-banner');
        })
        ->active()
        ->orderBy('sort')
        ->with(['media'])
        ->take(5)
        ->get();
@endphp

<section class="section-hero">
    <div class="relative z-10 overflow-hidden">
        <div class="pb-[60px] pt-28 md:pb-20 md:pt-36 lg:pb-[100px] lg:pt-[150px] xxl:pb-[120px] xxl:pt-[185px]">
            <div class="container-default">

                @if($heroBanners->isNotEmpty())

                    <div class="swiper hero-slider">
                        <div class="swiper-wrapper">
                            @foreach($heroBanners as $banner)
                                <div class="swiper-slide" data-banner-id="{{ $banner->id }}">
                                    <div class="grid gap-10 items-center lg:grid-cols-2 lg:gap-[74px] xxl:grid-cols-[1fr_minmax(0,_0.8fr)]">
                                        <!-- Hero Content Block -->
                                        <div class="text-center jos xl:text-left" data-jos_animation="fade-left">
                                            <h1 class="mb-6 font-ClashDisplay font-medium leading-[1.06] lg:text-[60px] text-color-oil xl:text-7xl xxl:text-[90px]">
                                                {{ $banner->title }}
                                            </h1>
                                            <p class="mb-8 text-color-oil lg:mb-[50px]">
                                                {!! nl2br(e($banner->description)) !!}
                                            </p>

                                            @if($banner->click_url)
                                                <div class="flex flex-wrap justify-center gap-6 xl:justify-start">
                                                    <a href="{{ $banner->click_url }}"
                                                       target="{{ $banner->click_url_target ?? '_self' }}"
                                                       class="inline-block btn is-outline-denim is-transparent is-large is-rounded btn-animation group banner-click-tracking"
                                                       onclick="trackBannerClick('{{ $banner->id }}')">
                                                        <span>{{ $banner->options['button_text'] ?? 'Learn More' }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Hero Content Block -->

                                        <!-- Hero Image Block -->
                                        <div class="jos mx-auto lg:mx-0 max-w-full sm:max-w-[80%] md:max-w-[70%] lg:max-w-full" data-jos_animation="fade-right">
                                            @if($banner->hasImage())
                                                <img src="{{ $banner->getImageUrl('large') }}"
                                                     srcset="{{ $banner->getImageUrl('medium') }} 768w, {{ $banner->getImageUrl('large') }} 1200w"
                                                     alt="{{ $banner->title }}"
                                                     width="526"
                                                     height="527"
                                                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                     class="w-full h-auto banner-image" />
                                            @else
                                                <img src="https://placehold.co/526x527"
                                                     alt="Placeholder Image"
                                                     width="526"
                                                     height="527"
                                                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                     class="w-full h-auto banner-image" />
                                            @endif
                                        </div>
                                        <!-- Hero Image Block -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($heroBanners->count() > 1)
                        <div class="mb-16 swiper-pagination hero-slider-pagination"></div>
                    @endif

                @endif

            </div>
        </div>
    </div>
</section>

@push('js')
<script>
    const heroSlider = new Swiper('.hero-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: {{ $heroBanners->count() > 1 ? 'true' : 'false' }},
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        navigation: false,
        pagination: {
            el: '.hero-slider-pagination',
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
