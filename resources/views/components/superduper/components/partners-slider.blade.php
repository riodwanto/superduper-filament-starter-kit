<section title="partners" class="relative flex flex-col items-center w-full py-16 c-container" data-aos="fade-up">
    <!-- Headline -->
    <h2 class="max-w-3xl px-8 mx-auto mb-3 font-bold text-2xl text-[#1E1E1E] leading-relaxed text-center lg:text-4xl lg:leading-relaxed lg:px-0">
        Trusted by
    </h2>
    <!-- Subtext -->
    <p class="max-w-2xl mx-auto text-lg text-gray-400">
        Brands worldwide leverage ours to drive measurable growth
    </p>

    @php
        $partnersMedia = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'partners')->get();
    @endphp

    <div x-data="partnerSlider()" x-init="initPartnerSlider" class="relative w-full mb-12 max-w-screen-2xl lg:max-w-screen-4xl">
        <div class="mx-16 md:mx-20 swiper sectionPartnerSlider">
            <div class="items-center swiper-wrapper">
                @foreach($partnersMedia as $media)
                    <div class="swiper-slide">
                        <div class="flex items-center justify-center w-full h-24 p-4 lg:h-32">
                            <img src="{{ $media->getFullUrl() }}" alt="{{ $media->name }}"
                                class="object-contain max-w-full max-h-full" loading="lazy" />
                        </div>
                        <div class="swiper-lazy-preloader"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('js')
    <script>
        function partnerSlider() {
            return {
                swiper: null,
                initPartnerSlider() {
                    this.swiper = new Swiper('.sectionPartnerSlider', {
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        loop: true,
                        speed: 800,
                        breakpoints: {
                            // when window width is >= 320px
                            320: {
                                slidesPerView: 1,
                                spaceBetween: 30
                            },
                            // when window width is >= 480px
                            480: {
                                slidesPerView: 2,
                                spaceBetween: 30
                            },
                            // when window width is >= 640px
                            640: {
                                slidesPerView: 5,
                                spaceBetween: 64
                            }
                        }
                    });
                }
            }
        }
    </script>
@endpush
