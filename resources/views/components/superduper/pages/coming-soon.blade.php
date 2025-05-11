<x-superduper.main>
    <div class="relative z-10 flex flex-col justify-center min-h-[90vh] bg-gradient-to-b from-blue-50 to-white">
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <div class="absolute bg-blue-400 rounded-full -top-24 -right-24 h-96 w-96 blur-3xl"></div>
            <div class="absolute bg-purple-400 rounded-full top-1/2 -left-24 h-96 w-96 blur-3xl"></div>
        </div>

        <div class="flex items-center flex-grow py-16">
            <div class="max-w-4xl px-4 mx-auto container-default md:px-6">
                <div class="flex flex-col items-center justify-center gap-8">

                    <div class="max-w-[720px] text-center">
                        <h1 class="text-4xl font-medium leading-tight text-gray-900 font-ClashDisplay md:text-5xl lg:text-6xl">
                            Something is <span class="text-blue-600">Coming Soon</span>
                        </h1>

                        <p class="max-w-2xl mx-auto mt-6 text-base text-gray-600 md:text-lg lg:text-xl">
                            "We're working on something amazing."
                        </p>
                    </div>

                    <div class="mt-10 lg:mt-12">
                        <div class="flex flex-wrap justify-center gap-4">
                            @php
                                $socialLinks = [
                                    'facebook' => $siteSocialSettings->facebook_url,
                                    'twitter' => $siteSocialSettings->twitter_url,
                                    'instagram' => $siteSocialSettings->instagram_url,
                                    'linkedin' => $siteSocialSettings->linkedin_url,
                                    'youtube' => $siteSocialSettings->youtube_url,
                                    'tiktok' => $siteSocialSettings->tiktok_url,
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
                                        class="flex items-center justify-center w-10 h-10 text-blue-600 transition-all duration-300 bg-blue-100 rounded-full hover:bg-blue-600 hover:text-white hover:scale-110"
                                        aria-label="{{ $platform }}">
                                        <i class="{{ $faIcons[$platform] ?? 'fa-brands fa-'.$platform }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-superduper.main>
