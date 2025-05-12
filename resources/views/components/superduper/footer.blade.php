<footer class="section-footer">
    <div class="bg-color-denim-darkblue">
        <div class="relative z-10">
            <div class="pb-[60px] pt-20 lg:pb-20 lg:pt-[100px] xl:pt-[120px]">
                <div class="container-default">
                    <div class="flex flex-col items-center justify-center gap-16">
                        <div class="max-w-[720px]">
                            <h2 class="text-3xl font-medium leading-loose text-center text-gray-100 lg:text-5xl xl:text-4xl">
                                Feel proud of everything you <br/> <span class="text-5xl font-bold text-secondary-600">Start</span> with <span class="text-5xl font-bold text-secondary-600">SuperDuper</span>
                            </h2>
                        </div>
                        <a href="{{ $siteSettings->footer_cta_button_url ?? '#' }}"
                            class="inline-block border border-gray-900 btn bg-secondary-700"><span>
                                Get started— it\'s free
                            </span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white horizontal-line"></div>

        <div class="text-white">
            <div class="py-[60px] lg:py-20">
                <div class="container-default">
                    <div class="grid gap-x-8 gap-y-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-[1fr_repeat(4,_auto)] xl:gap-x-10 xxl:gap-x-[134px]">
                        <div class="flex flex-col gap-y-7 md:col-span-3 lg:col-span-1">
                            <a href="{{ route('home') }}">
                                @php
                                    $brandLogo = $generalSettings->brand_logo ?? null;
                                    $brandName = $generalSettings->brand_name ?? $siteSettings->name ?? config('app.name', 'SuperDuper');
                                    $footerLogo = $siteSettings->footer_logo ?? $brandLogo;
                                @endphp

                                @if($footerLogo)
                                    <img src="{{ Storage::url($footerLogo) }}" alt="{{ $brandName }}" width="220" height="auto" />
                                @endif
                            </a>

                            <div>
                                <div class="lg:max-w-[416px]">
                                    {{ $siteSettings->description ?? '' }}
                                </div>

                                <a href="mailto:{{ $siteSettings->company_email ?? 'yourdemo@email.com' }}"
                                    class="block my-6 transition-all duration-300 underline-offset-4 hover:underline">
                                    {{ $siteSettings->company_email ?? 'yourdemo@email.com' }}
                                </a>

                                <div class="flex flex-wrap gap-5">
                                    @php
                                        $socialLinks = [
                                            'facebook' => $siteSocialSettings->facebook_url ?? null,
                                            'twitter' => $siteSocialSettings->twitter_url ?? null,
                                            'instagram' => $siteSocialSettings->instagram_url ?? null,
                                            'linkedin' => $siteSocialSettings->linkedin_url ?? null,
                                            'youtube' => $siteSocialSettings->youtube_url ?? null,
                                            'tiktok' => $siteSocialSettings->tiktok_url ?? null,
                                        ];

                                        $faIcons = [
                                            'twitter' => 'fa-brands fa-x-twitter',
                                            'facebook' => 'fa-brands fa-facebook-f',
                                            'instagram' => 'fa-brands fa-instagram',
                                            'linkedin' => 'fa-brands fa-linkedin-in',
                                            'youtube' => 'fa-brands fa-youtube',
                                            'tiktok' => 'fa-brands fa-tiktok',
                                        ];
                                    @endphp

                                    @foreach($socialLinks as $platform => $url)
                                        @if(!empty($url))
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                                class="flex h-[30px] w-[30px] items-center justify-center rounded-[50%] bg-white bg-opacity-5 text-sm text-white transition-all duration-300 hover:bg-color-pale-gold hover:text-color-denim-darkblue"
                                                aria-label="{{ $platform }}">
                                                <i class="{{ $faIcons[$platform] ?? 'fa-brands fa-'.$platform }}"></i>
                                            </a>
                                        @endif
                                    @endforeach

                                    @if(empty(array_filter($socialLinks)))
                                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                                            class="flex h-[30px] w-[30px] items-center justify-center rounded-[50%] bg-white bg-opacity-5 text-sm text-white transition-all duration-300 hover:bg-color-pale-gold hover:text-color-denim-darkblue"
                                            aria-label="twitter">
                                            <i class="fa-brands fa-x-twitter"></i>
                                        </a>
                                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"
                                            class="flex h-[30px] w-[30px] items-center justify-center rounded-[50%] bg-white bg-opacity-5 text-sm text-white transition-all duration-300 hover:bg-color-pale-gold hover:text-color-denim-darkblue"
                                            aria-label="facebook">
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-y-7">
                            <div class="text-xl font-semibold capitalize">
                                Main
                            </div>
                            @php
                                use Datlechin\FilamentMenuBuilder\Models\Menu;
                                $footerMenu = Menu::location('footer');
                            @endphp
                            <ul class="flex flex-col gap-y-[10px] capitalize">
                                @if($footerMenu)
                                    @foreach($footerMenu->menuItems as $item)
                                        <li>
                                            <a href="{{ $item->url }}" @if($item->target) target="{{ $item->target }}" @endif
                                                class="transition-all duration-300 ease-linear hover:opcity-100 underline-offset-4 opacity-80 hover:underline">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <a href="{{ route('home') }}"
                                            class="transition-all duration-300 ease-linear hover:opcity-100 underline-offset-4 opacity-80 hover:underline">Home</a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="flex flex-col gap-y-6">
                            <div class="text-xl font-semibold capitalize">
                                Sample Pages
                            </div>
                            @php
                                $footerOthers = Menu::location('footer-2');
                            @endphp
                            <ul class="flex flex-col gap-y-[10px] capitalize">
                                @if($footerOthers)
                                    @foreach($footerOthers->menuItems as $item)
                                        <li>
                                            <a href="{{ $item->url }}" @if($item->target) target="{{ $item->target }}" @endif
                                                class="transition-all duration-300 ease-linear hover:opcity-100 underline-offset-4 opacity-80 hover:underline">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        {{-- # TODO: Create Menu Module --}}
                        <div class="flex flex-col gap-y-6">
                            <div class="text-xl font-semibold capitalize">
                                Resources
                            </div>
                            @php
                                $footerOthers = Menu::location('footer-3');
                            @endphp
                            <ul class="flex flex-col gap-y-[10px] capitalize">
                                @if($footerOthers)
                                    @foreach($footerOthers->menuItems as $item)
                                        <li>
                                            <a href="{{ $item->url }}" @if($item->target) target="{{ $item->target }}" @endif
                                                class="transition-all duration-300 ease-linear hover:opcity-100 underline-offset-4 opacity-80 hover:underline">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        {{-- # TODO: Create Menu Module --}}
                        <div class="flex flex-col gap-y-6">
                            <div class="text-xl font-semibold capitalize">
                                Community
                            </div>
                            @php
                                $footerOthers = Menu::location('footer-4');
                            @endphp
                            <ul class="flex flex-col gap-y-[10px] capitalize">
                                @if($footerOthers)
                                    @foreach($footerOthers->menuItems as $item)
                                        <li>
                                            <a href="{{ $item->url }}" @if($item->target) target="{{ $item->target }}" @endif
                                                class="transition-all duration-300 ease-linear hover:opcity-100 underline-offset-4 opacity-80 hover:underline">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white bg-opacity-5">
            <div class="py-[18px]">
                <div class="container-default">
                    <div class="text-center text-white text-opacity-80">
                        &copy; Copyright {{ date('Y') }}, {{ $siteSettings->copyright_text ?? 'All Rights Reserved' }}
                        {{ $generalSettings->brand_name ?? $siteSettings->name ?? config('app.name', 'SuperDuper') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
