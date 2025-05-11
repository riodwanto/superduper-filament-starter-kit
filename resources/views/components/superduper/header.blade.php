<header class="fixed z-50 w-full py-4 transition-all duration-300 bg-transparent md:py-6">
    <div class="px-4 mx-auto container-default">
        <div class="flex items-center justify-between gap-x-4 md:gap-x-8">
            <!-- Header Logo -->
            <a href="{{ route('home') }}" class="relative z-10 flex-shrink-0">
                @php
                    $brandLogo = $generalSettings->brand_logo ?? null;
                    $brandName = $generalSettings->brand_name ?? $siteSettings->name ?? config('app.name', 'SuperDuper');
                @endphp

                @if($brandLogo)
                    <img src="{{ Storage::url($brandLogo) }}"
                         alt="{{ $brandName }}"
                         class="w-auto h-10 md:h-12"
                    />
                @else
                    <div class="flex items-center">
                        <span class="text-xl font-bold md:text-2xl text-primary-800 dark:text-white header-brand-text">{{ $brandName }}</span>
                    </div>
                @endif
            </a>

            <!-- Header Navigation -->
            <div class="menu-block-wrapper lg:static">
                <div class="fixed inset-0 z-40 menu-overlay bg-primary-900/70 backdrop-blur-sm lg:hidden" style="display: none;"></div>
                <nav class="menu-block fixed top-0 right-0 bottom-0 w-[280px] text-secondary-600 md:w-[320px] dark:bg-primary-800 z-50 shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300 lg:static lg:translate-x-0 lg:w-auto lg:bg-transparent lg:shadow-none lg:overflow-visible lg:dark:bg-transparent" id="append-menu-header">
                    <div class="flex items-center justify-between p-4 border-b border-background-light lg:hidden">
                        <div class="flex items-center go-back text-primary-800 dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Back</span>
                        </div>
                        <div class="font-medium current-menu-title text-primary-800 dark:text-white"></div>
                        <div class="text-2xl cursor-pointer mobile-menu-close text-primary-800 dark:text-white">&times;</div>
                    </div>

                    @php
                        use Datlechin\FilamentMenuBuilder\Models\Menu;
                        $menu = Menu::location('header');
                    @endphp

                    <ul class="p-4 text-lg site-menu-main lg:p-0 lg:flex lg:items-center lg:space-x-1">
                        @if($menu)
                            @foreach($menu->menuItems as $index => $item)
                                @php
                                    $hasChildren = count($item->children) > 0;
                                    $menuId = 'submenu-' . ($index + 1);
                                @endphp

                                <li class="nav-item mb-3 lg:mb-0 lg:relative {{ $hasChildren ? 'nav-item-has-children' : '' }}">
                                    <a href="{{ $item->url }}"
                                       class="nav-link-item flex items-center justify-between text-white hover:text-primary-600 dark:text-white dark:hover:text-primary-200 header-nav-link font-medium py-2 lg:px-3 lg:hover:bg-primary-600 lg:dark:hover:bg-primary-700 transition-colors {{ $hasChildren ? 'drop-trigger' : '' }}"
                                       @if($item->target) target="{{ $item->target }}" @endif>
                                        <span>{{ $item->title }}</span>
                                        @if($hasChildren)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </a>

                                    @if($hasChildren)
                                        <ul class="sub-menu pl-4 mt-2 lg:absolute lg:left-0 lg:top-full lg:mt-1 lg:pl-0 lg:min-w-[200px] lg:bg-white lg:dark:bg-primary-800 lg:shadow-lg lg:opacity-0 lg:invisible lg:transform lg:translate-y-2 lg:transition-all lg:group-hover:opacity-100 lg:group-hover:visible lg:group-hover:translate-y-0 lg:z-20" id="{{ $menuId }}">
                                            @foreach($item->children as $childIndex => $childItem)
                                                @php
                                                    $hasGrandchildren = count($childItem->children) > 0;
                                                    $submenuId = $menuId . '-' . ($childIndex + 1);
                                                @endphp

                                                <li class="sub-menu--item mb-2 lg:mb-0 {{ $hasGrandchildren ? 'nav-item-has-children' : '' }}">
                                                    <a href="{{ $childItem->url }}"
                                                       class="block px-3 py-2 text-white transition-colors hover:text-primary-600 dark:text-white dark:hover:text-primary-200 lg:hover:bg-primary-50 lg:dark:hover:bg-primary-700 lg:rounded"
                                                       @if($hasGrandchildren) data-menu-get="h3" class="flex items-center justify-between drop-trigger" @endif
                                                       @if($childItem->target) target="{{ $childItem->target }}" @endif>
                                                        <span>{{ $childItem->title }}</span>
                                                        @if($hasGrandchildren)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        @endif
                                                    </a>

                                                    @if($hasGrandchildren)
                                                        <ul class="sub-menu pl-4 mt-2 lg:absolute lg:left-full lg:top-0 lg:pl-0 lg:mt-0 lg:min-w-[200px] lg:bg-white lg:dark:bg-primary-800 lg:shadow-lg lg:opacity-0 lg:invisible lg:transform lg:translate-x-2 lg:transition-all lg:group-hover:opacity-100 lg:group-hover:visible lg:group-hover:translate-x-0" id="{{ $submenuId }}">
                                                            @foreach($childItem->children as $grandchildItem)
                                                                <li class="mb-2 sub-menu--item lg:mb-0">
                                                                    <a href="{{ $grandchildItem->url }}"
                                                                       class="block px-3 py-2 text-white transition-colors hover:text-primary-600 dark:text-white dark:hover:text-primary-200 lg:hover:bg-primary-50 lg:dark:hover:bg-primary-700 lg:rounded"
                                                                       @if($grandchildItem->target) target="{{ $grandchildItem->target }}" @endif>
                                                                        {{ $grandchildItem->title }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </nav>
            </div>

            <!-- Header Event -->
            <div class="flex items-center gap-4 md:gap-6">
                <a href="admin/login" class="relative z-10 hidden sm:inline-block group">
                    <div class="px-4 py-2 text-sm font-medium transition-all duration-300 btn md:text-base bg-secondary-600 hover:bg-secondary-700">Admin Panel</div>
                    <div class="absolute inset-0 -z-10 translate-x-[3px] translate-y-[3px] bg-primary-700 transition-all duration-300 ease-linear group-hover:translate-x-0 group-hover:translate-y-0"></div>
                </a>

                <div class="block lg:hidden">
                    <button id="openBtn" class="flex flex-col items-center justify-center w-10 h-10 text-red-500 rounded-md hamburger-menu mobile-menu-trigger focus:outline-none focus:ring-2 focus:ring-primary-600">
                        <span class="block w-6 h-0.5 bg-primary-800 dark:bg-white mb-1.5 transition-transform hamburger-line"></span>
                        <span class="block w-6 h-0.5 bg-primary-800 dark:bg-white mb-1.5 transition-opacity hamburger-line"></span>
                        <span class="block w-6 h-0.5 bg-primary-800 dark:bg-white transition-transform hamburger-line"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuTrigger = document.querySelector('.mobile-menu-trigger');
    const menuOverlay = document.querySelector('.menu-overlay');
    const menuBlock = document.querySelector('.menu-block');
    const menuClose = document.querySelector('.mobile-menu-close');
    const dropTriggers = document.querySelectorAll('.drop-trigger');
    const goBack = document.querySelector('.go-back');
    const currentMenuTitle = document.querySelector('.current-menu-title');
    
    function toggleMenu() {
        menuBlock.classList.toggle('translate-x-full');
        document.body.classList.toggle('overflow-hidden');
        menuOverlay.style.display = menuBlock.classList.contains('translate-x-full') ? 'none' : 'block';
        
        const spans = menuTrigger.querySelectorAll('span');
        if (!menuBlock.classList.contains('translate-x-full')) {
            spans[0].classList.add('rotate-45', 'translate-y-2');
            spans[1].classList.add('opacity-0');
            spans[2].classList.add('-rotate-45', '-translate-y-2');
        } else {
            spans[0].classList.remove('rotate-45', 'translate-y-2');
            spans[1].classList.remove('opacity-0');
            spans[2].classList.remove('-rotate-45', '-translate-y-2');
        }
    }
    
    menuTrigger.addEventListener('click', toggleMenu);
    menuOverlay.addEventListener('click', toggleMenu);
    menuClose.addEventListener('click', toggleMenu);
    
    function updateHeaderBackground() {
        const header = document.querySelector('header');
        const navLinks = document.querySelectorAll('.header-nav-link');
        const brandText = document.querySelector('.header-brand-text');
        const hamburgerLines = document.querySelectorAll('.hamburger-line');
        
        if (window.scrollY > 50) {
            header.classList.add('bg-primary-800', 'dark:bg-primary-900', 'shadow-md', 'py-3', 'md:py-4');
            
            navLinks.forEach(link => {
                link.classList.remove('text-white', 'hover:text-primary-600');
                link.classList.add('text-white', 'hover:text-primary-100');
            });
            
            if (brandText) {
                brandText.classList.remove('text-primary-800');
                brandText.classList.add('text-white');
            }
            
            hamburgerLines.forEach(line => {
                line.classList.remove('bg-primary-800');
                line.classList.add('bg-white');
            });
        } else {
            header.classList.remove('bg-primary-800', 'dark:bg-primary-900', 'shadow-md', 'py-3', 'md:py-4');
            
            navLinks.forEach(link => {
                link.classList.add('text-white', 'hover:text-primary-600');
                link.classList.remove('text-white', 'hover:text-primary-100');
            });
            
            if (brandText) {
                brandText.classList.add('text-primary-800');
                brandText.classList.remove('text-white');
            }
            
            hamburgerLines.forEach(line => {
                line.classList.add('bg-primary-800');
                line.classList.remove('bg-white');
            });
        }
    }
    
    window.addEventListener('scroll', updateHeaderBackground);
    updateHeaderBackground(); // Initial check
    
    if (window.innerWidth < 1024) {
        dropTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth < 1024) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const submenu = parent.querySelector('.sub-menu');
                    const title = this.querySelector('span').textContent;
                    
                    if (submenu) {
                        submenu.style.display = 'block';
                        currentMenuTitle.textContent = title;
                        parent.parentElement.style.display = 'none';
                        document.querySelector('.go-back').style.display = 'flex';
                    }
                }
            });
        });
        
        goBack.addEventListener('click', function() {
            const activeSubmenu = document.querySelector('.sub-menu[style="display: block;"]');
            if (activeSubmenu) {
                activeSubmenu.style.display = 'none';
                activeSubmenu.parentElement.parentElement.style.display = 'block';
                
                if (activeSubmenu.parentElement.parentElement.classList.contains('site-menu-main')) {
                    currentMenuTitle.textContent = '';
                    this.style.display = 'none';
                } else {
                    const parentTrigger = activeSubmenu.parentElement.parentElement.previousElementSibling;
                    if (parentTrigger && parentTrigger.classList.contains('drop-trigger')) {
                        currentMenuTitle.textContent = parentTrigger.querySelector('span').textContent;
                    }
                }
            }
        });
    }
});
</script>
@endpush
