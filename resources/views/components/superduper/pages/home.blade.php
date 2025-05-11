<x-superduper.main>

    <div class="page-wrapper relative z-[1]">
        <main class="relative overflow-hidden main-wrapper">

            <x-superduper.components.hero />
            
            <x-superduper.components.value-proposition />

            <x-superduper.components.packages-plugins />

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
                        <livewire:superduper.blog-section-slider
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

            <div class="container px-4 py-16 mx-auto">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-3xl font-bold">Frequently Asked Questions</h2>
                    <p class="max-w-2xl mx-auto text-lg">Get answers to the most common questions about SuperDuper Starter Kit</p>
                </div>
                
                <div class="max-w-4xl mx-auto divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="py-6">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="text-xl font-semibold">What makes this different from other Filament starter kits?</h3>
                        </div>
                        <div class="mt-4" x-show="open">
                            <p class="text-gray-700 dark:text-gray-300">SuperDuper provides a complete ecosystem, not just scaffolding. It includes integrated modules for content management, user management, media handling, and more. Our focus on developer experience means cleaner code organization, better documentation, and pre-built solutions for common requirements like multilingual support and SEO optimization.</p>
                        </div>
                    </div>
                    
                    <div class="py-6">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="text-xl font-semibold">Can I use this for commercial projects?</h3>
                        </div>
                        <div class="mt-4" x-show="open">
                            <p class="text-gray-700 dark:text-gray-300">Yes! SuperDuper is released under the MIT license, which means you can use it for personal or commercial projects without restrictions. You're free to modify, distribute, and use it in your own work without attribution, though a shoutout is always appreciated!</p>
                        </div>
                    </div>
                    
                    <div class="py-6">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="text-xl font-semibold">Do I need to know Laravel or Filament to use this?</h3>
                        </div>
                        <div class="mt-4" x-show="open">
                            <p class="text-gray-700 dark:text-gray-300">Basic familiarity with Laravel and Filament is recommended. However, the starter kit is designed to be intuitive, with thorough documentation to help you understand the structure. If you're new to Filament, this kit actually makes it easier to learn by providing working examples of best practices.</p>
                        </div>
                    </div>
                    
                    <div class="py-6">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="text-xl font-semibold">How do updates work with this starter kit?</h3>
                        </div>
                        <div class="mt-4" x-show="open">
                            <p class="text-gray-700 dark:text-gray-300">Once you create a project from the starter kit, it becomes your own codebase. We regularly release updates to the template itself, but applying these to an existing project is manual. For critical updates, we provide migration guides in our documentation to help you integrate new features or security patches.</p>
                        </div>
                    </div>
                    
                    <div class="py-6">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="text-xl font-semibold">Is there support available if I run into issues?</h3>
                        </div>
                        <div class="mt-4" x-show="open">
                            <p class="text-gray-700 dark:text-gray-300">Yes, you can open issues on our GitHub repository and you can often find answers to common questions in our documentation or from other developers. For dedicated support or custom development, you can contact the maintainers directly.</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</x-superduper.main>
