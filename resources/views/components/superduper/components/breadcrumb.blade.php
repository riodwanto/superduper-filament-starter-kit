@props(['title' => 'Blog', 'items' => []])

<section class="relative overflow-hidden section-breadcrumb">
    <div class="relative breadcrumb-wrapper">
        <div class="container-default">
            <div class="breadcrumb-block">
                <h1 class="breadcrumb-title">{{ $title }}</h1>
                <ul class="breadcrumb-nav">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @foreach($items as $item)
                        @if(isset($item['url']))
                            <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                        @else
                            <li>{{ $item['label'] }}</li>
                        @endif
                    @endforeach
                    @if(count($items) === 0)
                        <li>{{ $title }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="absolute top-0 left-0 -z-10 w-72 h-96 opacity-60">
            <div class="absolute inset-0 transform -translate-x-1/2 rounded-full bg-gradient-to-br from-blue-500/30 to-purple-600/40 blur-3xl"></div>
            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-cyan-400/20 to-emerald-500/30 blur-3xl rotate-12"></div>
        </div>

        <div class="absolute bottom-0 right-0 -z-10 w-72 h-96 opacity-60">
            <div class="absolute inset-0 transform translate-x-1/2 rounded-full bg-gradient-to-tl from-amber-400/30 to-rose-500/40 blur-3xl"></div>
            <div class="absolute inset-0 rounded-full bg-gradient-to-l from-blue-400/20 to-indigo-500/30 blur-3xl -rotate-12"></div>
        </div>
    </div>
</section>

