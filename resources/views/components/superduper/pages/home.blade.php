<x-superduper.main>

    <div class="page-wrapper relative z-[1]">
    <main class="relative overflow-hidden main-wrapper">

            <x-superduper.components.hero />
            
            <x-superduper.components.value-proposition />

            <x-superduper.components.packages-plugins />

            <!-- Carousel -->
            <x-superduper.components.partners-slider />

            {{-- Showcases --}}
            <div class="relative py-8 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-background-white to-background-wheat dark:from-primary-900 dark:to-primary-800 -z-10"></div>
                
                <div class="container px-4 py-16 mx-auto">
                    <div class="mb-16 text-center">
                        <span class="inline-block px-4 py-1 mb-3 text-sm font-medium rounded-full bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200">Content Management</span>
                        <h2 class="mb-4 font-bold">Feature-Rich Blog Platform, Ready to Publish</h2>
                        <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">Launch your content strategy immediately with SuperDuper's integrated blog system</p>
                    </div>
                    
                    <!-- Blog Showcase -->
                    <div class="mb-16">
                        <livewire:super-duper.blog-section-slider
                            :limit="6"
                            :featured-only="false"
                            :category-slug="null"
                        />
                    </div>
                    
                    <!-- Banner Showcase -->
                    <div class="pt-8 border-t border-background-light dark:border-primary-700">
                        <div class="mb-10 text-center">
                            <span class="inline-block px-4 py-1 mb-3 text-sm font-medium rounded-full bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200">Banner Management</span>
                            <h2 class="mb-4 text-3xl font-bold">Engaging Banner System</h2>
                            <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">Create eye-catching banners with intuitive management interface</p>
                        </div>
                        
                        <div class="max-w-5xl mx-auto overflow-hidden bg-white rounded-lg shadow-lg">
                            <x-superduper.components.banner />
                        </div>
                        
                        <div class="mt-8 text-center">
                            <div class="inline-flex items-center justify-center px-4 py-2 font-medium rounded-lg text-primary-700 bg-primary-100 dark:bg-primary-900 dark:text-primary-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Both blog and banner modules are fully customizable through our admin interface</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute bottom-0 left-0 right-0 h-16 bg-white dark:bg-gray-800 -z-10" style="clip-path: polygon(0 100%, 100% 0, 100% 100%, 0% 100%);"></div>
            </div>


        </main>
    </div>

</x-superduper.main>
