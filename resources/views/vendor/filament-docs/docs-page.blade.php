@php
    $sections = $this->getManualSections();
    $currentSection = $this->getCurrentSection();
    $filteredSections = $this->getFilteredSections();
    $searchResults = $this->getSearchResults();
@endphp

<x-filament-panels::page>
    <div x-data="{
        printCurrent: @entangle('isPrintingCurrent'),
        printAll: @entangle('isPrintingAll'),
        sidebarCollapsed: false
    }" x-init="$watch('printCurrent', value => {
        if (value) {
            document.body.classList.add('print-current-section');
            setTimeout(() => {
                window.print();
                $wire.resetPrintFlags();
                document.body.classList.remove('print-current-section');
            }, 100);
        }
    });
    $watch('printAll', value => {
        if (value) {
            document.body.classList.add('print-all-sections');
            setTimeout(() => {
                window.print();
                $wire.resetPrintFlags();
                document.body.classList.remove('print-all-sections');
            }, 100);
        }
    });"
        :class="{
            'print-current-section': printCurrent,
            'print-all-sections': printAll
        }">
        <div>
            <style>
                /* Table styling for better rendering */
                .dark .prose {
                    color: white !important;
                }

                .dark .prose h1 {
                    color: white !important;
                }
                .dark .prose h2 {
                    color: white !important;
                }
                .dark .prose h3 {
                    color: white !important;
                }
                .dark .prose h4 {
                    color: white !important;
                }
                .dark .prose h5 {
                    color: white !important;
                }
                .dark .prose h6 {
                    color: white !important;
                }
                .dark .prose h6 {
                    color: white !important;
                }
                .dark .prose code {
                    color: white !important;
                }
                .dark .prose strong {
                    color: white !important;
                    font-weight: bold !important;
                }
                .prose table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1.5rem 0;
                    font-size: 0.875rem;
                    line-height: 1.25rem;
                    border: 1px solid rgb(229 231 235);
                    border-radius: 0.5rem;
                    overflow: hidden;
                }

                .prose thead {
                    background-color: rgb(249 250 251);
                }

                .dark .prose thead {
                    background-color: rgb(31 41 55);
                }

                .prose th {
                    padding: 0.75rem 1rem;
                    text-align: left;
                    font-weight: 600;
                    color: rgb(55 65 81);
                    border-bottom: 1px solid rgb(229 231 235);
                    border-right: 1px solid rgb(229 231 235);
                }

                .dark .prose th {
                    color: rgb(209 213 219);
                    border-bottom: 1px solid rgb(75 85 99);
                    border-right: 1px solid rgb(75 85 99);
                }

                .prose th:last-child {
                    border-right: none;
                }

                .prose td {
                    padding: 0.75rem 1rem;
                    border-bottom: 1px solid rgb(229 231 235);
                    border-right: 1px solid rgb(229 231 235);
                    color: rgb(55 65 81);
                    vertical-align: top;
                }

                .dark .prose td {
                    color: rgb(209 213 219);
                    border-bottom: 1px solid rgb(75 85 99);
                    border-right: 1px solid rgb(75 85 99);
                }

                .prose td:last-child {
                    border-right: none;
                }

                .prose tbody tr:last-child td {
                    border-bottom: none;
                }

                .prose tbody tr:nth-child(even) {
                    background-color: rgb(249 250 251);
                }

                .dark .prose tbody tr:nth-child(even) {
                    background-color: rgb(31 41 55);
                }

                .prose tbody tr:hover {
                    background-color: rgb(243 244 246);
                }

                .dark .prose tbody tr:hover {
                    background-color: rgb(55 65 81);
                }

                /* Handle table overflow on small screens */
                .prose .table-container {
                    overflow-x: auto;
                    margin: 1.5rem 0;
                    border-radius: 0.5rem;
                    border: 1px solid rgb(229 231 235);
                }

                .dark .prose .table-container {
                    border: 1px solid rgb(75 85 99);
                }

                #filament_docs_search_input {
                    padding-left: 30px !important;
                    border-color: transparent !important;
                }

                @media print {

                    /* Hide everything except the content by default */
                    .lg\:w-80,
                    .flex-shrink-0,
                    nav,
                    .border-t,
                    button,
                    .bg-gradient-to-r,
                    .flex.space-x-2,
                    .bg-gray-50,
                    .dark\:bg-gray-800 {
                        display: none !important;
                    }

                    /* Show only the main content area */
                    .flex-1.min-w-0 {
                        width: 100% !important;
                        max-width: none !important;
                    }

                    /* Hide navigation elements and headers */
                    .bg-gray-50.dark\:bg-gray-800,
                    .bg-gradient-to-r.from-primary-50,
                    .dark\:from-primary-900\/20 {
                        display: none !important;
                    }

                    /* Default: print only current section */
                    .main-content-area>div {
                        display: none !important;
                    }

                    .main-content-area>div.block {
                        display: block !important;
                    }

                    /* Hide print sections by default */
                    .print-section {
                        display: none !important;
                    }

                    /* When printing current section only */
                    body.print-current-section .main-content-area>div:not(.block) {
                        display: none !important;
                    }

                    body.print-current-section .main-content-area>div.block {
                        display: block !important;
                    }

                    body.print-current-section .main-content-area .prose {
                        display: block !important;
                        visibility: visible !important;
                    }

                    /* When printing all sections, show all content */
                    body.print-all-sections .main-content-area>div {
                        display: block !important;
                        page-break-before: always;
                    }

                    body.print-all-sections .main-content-area>div:first-child {
                        page-break-before: auto;
                    }

                    body.print-all-sections .print-section {
                        display: block !important;
                        page-break-before: always;
                    }

                    /* Style the content for print */
                    .bg-white.dark\:bg-gray-900 {
                        background: white !important;
                        box-shadow: none !important;
                        border: none !important;
                        border-radius: 0 !important;
                        display: block !important;
                    }

                    /* Ensure the content area is visible */
                    .p-8 {
                        display: block !important;
                        padding: 20pt !important;
                    }

                    /* Content styling */
                    .prose {
                        max-width: none !important;
                        font-size: 12pt !important;
                        line-height: 1.6 !important;
                        display: block !important;
                        visibility: visible !important;
                    }

                    /* Ensure section content is visible */
                    .prose * {
                        display: block !important;
                        visibility: visible !important;
                    }

                    .prose h1 {
                        font-size: 18pt !important;
                        color: #000 !important;
                        border-bottom: 2px solid #000 !important;
                        padding-bottom: 8pt !important;
                        margin-bottom: 16pt !important;
                        page-break-after: avoid !important;
                    }

                    .prose h2 {
                        font-size: 16pt !important;
                        color: #000 !important;
                        margin-top: 20pt !important;
                        margin-bottom: 12pt !important;
                        page-break-after: avoid !important;
                    }

                    .prose h3 {
                        font-size: 14pt !important;
                        color: #000 !important;
                        margin-top: 16pt !important;
                        margin-bottom: 10pt !important;
                        page-break-after: avoid !important;
                    }

                    .prose p {
                        margin-bottom: 12pt !important;
                        color: #000 !important;
                    }

                    .prose ul,
                    .prose ol {
                        margin-bottom: 12pt !important;
                        padding-left: 24pt !important;
                    }

                    .prose li {
                        margin-bottom: 4pt !important;
                        color: #000 !important;
                    }

                    .prose code {
                        background-color: #f5f5f5 !important;
                        padding: 2pt 4pt !important;
                        border-radius: 3pt !important;
                        font-family: 'Courier New', monospace !important;
                        font-size: 10pt !important;
                        color: #000 !important;
                    }

                    .prose pre {
                        background-color: #f5f5f5 !important;
                        padding: 12pt !important;
                        border-radius: 0 !important;
                        border: 1px solid #ccc !important;
                        font-family: 'Courier New', monospace !important;
                        font-size: 10pt !important;
                        color: #000 !important;
                        page-break-inside: avoid !important;
                        overflow: visible !important;
                    }

                    .prose blockquote {
                        border-left: 4pt solid #000 !important;
                        padding-left: 16pt !important;
                        margin: 16pt 0 !important;
                        font-style: italic !important;
                        color: #000 !important;
                    }

                    .prose table {
                        border-collapse: collapse !important;
                        width: 100% !important;
                        margin-bottom: 16pt !important;
                        page-break-inside: avoid !important;
                    }

                    .prose th,
                    .prose td {
                        border: 1pt solid #000 !important;
                        padding: 8pt 12pt !important;
                        text-align: left !important;
                        font-size: 10pt !important;
                        color: #000 !important;
                    }

                    .prose th {
                        background-color: #f5f5f5 !important;
                        font-weight: 600 !important;
                    }

                    .prose img {
                        max-width: 100% !important;
                        height: auto !important;
                        page-break-inside: avoid !important;
                    }

                    /* Page setup */
                    @page {
                        size: A4;
                        margin: 2cm;
                    }

                    body {
                        print-color-adjust: exact !important;
                        -webkit-print-color-adjust: exact !important;
                    }

                    /* Special styling for full documentation print */
                    body.print-all-sections .prose h1:first-child::before {
                        content: "Complete Documentation";
                        display: block;
                        font-size: 24pt;
                        text-align: center;
                        margin-bottom: 2cm;
                        page-break-after: always;
                    }
                }
            </style>
            <div class="flex flex-col lg:flex-row gap-6 min-h-screen">
                <!-- Left Sidebar Navigation -->
                <div class="lg:w-80 flex-shrink-0" x-show="!sidebarCollapsed || window.innerWidth < 1024"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-full"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-full">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 sticky top-6">
                        <!-- Sidebar Header with Search -->
                        <div class="bg-primary-600 text-white p-4 rounded-t-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-bold flex items-center">
                                    <x-heroicon-o-list-bullet class="w-5 h-5 mr-2" />
                                    {{ __('filament-docs::docs.navigation.sections') }}
                                </h2>
                                <button @click="sidebarCollapsed = true"
                                    class="lg:block hidden p-1 hover:bg-primary-700 rounded transition-colors">
                                    <x-heroicon-o-x-mark class="w-4 h-4" />
                                </button>
                            </div> <!-- Search Box -->
                            <div class="relative">
                                <input type="text" id="filament_docs_search_input" wire:model.live.debounce.300ms="searchQuery"
                                    placeholder="{{ __('filament-docs::docs.search.placeholder') }}"
                                    class="w-full px-3 py-2 pl-9 pr-9 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border border-white/20 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <x-heroicon-o-magnifying-glass
                                    class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500" />
                                @if (!empty($this->searchQuery))
                                    <button wire:click="clearSearch"
                                        class="absolute right-2.5 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <x-heroicon-o-x-mark class="w-4 h-4" />
                                    </button>
                                @endif
                            </div>

                            <p class="text-sm opacity-90 mt-2">
                                @if (!empty($this->searchQuery))
                                    {{ trans_choice('filament-docs::docs.search.results_count', count($searchResults), ['count' => count($searchResults)]) }}
                                @else
                                    {{ trans_choice('filament-docs::docs.navigation.sections_count', count($sections), ['count' => count($sections)]) }}
                                @endif
                            </p>
                        </div> <!-- Navigation Menu -->
                        <nav class="p-2 max-h-96 overflow-y-auto">
                            @if (empty($sections))
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    <x-heroicon-o-exclamation-circle
                                        class="w-8 h-8 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                                    <p class="text-sm">{{ __('filament-docs::docs.empty.no_documentation') }}</p>
                                </div>
                            @elseif(!empty($this->searchQuery))
                                <!-- Search Results -->
                                @if (empty($searchResults))
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <x-heroicon-o-magnifying-glass
                                            class="w-8 h-8 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                                        <p class="text-sm">
                                            {{ __('filament-docs::docs.search.no_results', ['query' => $this->searchQuery]) }}
                                        </p>
                                        <button wire:click="clearSearch"
                                            class="mt-2 text-xs text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                            {{ __('filament-docs::docs.search.clear') }}
                                        </button>
                                    </div>
                                @else
                                    @foreach ($searchResults as $result)
                                        <div class="mb-3 border-b border-gray-100 pb-3 last:border-b-0">
                                            <button wire:click="selectSection('{{ $result['section']['id'] }}')"
                                                class="w-full text-left p-3 rounded-lg transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800 group">

                                                <div class="flex items-start">
                                                    <div
                                                        class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">
                                                        @php
                                                            $sectionIndex = 0;
                                                            foreach ($sections as $index => $section) {
                                                                if ($section['id'] === $result['section']['id']) {
                                                                    $sectionIndex = $index + 1;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $sectionIndex }}
                                                    </div>

                                                    <div class="flex-1 min-w-0">
                                                        <h4
                                                            class="font-medium text-sm text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                                            {!! $this->highlightSearchTerm($result['section']['title'], $this->searchQuery) !!}
                                                        </h4>
                                                        @if ($result['total_matches'] > 0)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ trans_choice('filament-docs::docs.search.matches_found', $result['total_matches'], ['count' => $result['total_matches']]) }}
                                                            </p>
                                                            @foreach ($result['matches'] as $match)
                                                                <div
                                                                    class="text-xs text-gray-600 dark:text-gray-300 mt-1 truncate">
                                                                    {!! $match['highlighted'] !!}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            @else
                                <!-- Regular Section Navigation -->
                                @foreach ($sections as $section)
                                    <button wire:click="selectSection('{{ $section['id'] }}')"
                                        class="w-full text-left p-3 rounded-lg mb-1 transition-all duration-200 group flex items-center {{ $section['id'] === $this->selectedSection ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 border-l-4 border-primary-600' : 'hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}">

                                        {{-- <div
                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold mr-3 {{ $section['id'] === $this->selectedSection ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600' }}">
                                            {{ $loop->iteration }}
                                        </div> --}}

                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="font-medium text-sm block truncate">{{ $section['title'] }}</span>
                                        </div>

                                        @if ($section['id'] === $this->selectedSection)
                                            <x-heroicon-o-chevron-right
                                                class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                                        @endif
                                    </button>
                                @endforeach
                            @endif
                        </nav>
                    </div>
                </div> <!-- Main Content Area -->
                <div class="flex-1 min-w-0 main-content-area" :class="{ 'lg:ml-0': sidebarCollapsed }">
                    <!-- Collapsed Sidebar Toggle Button -->
                    <div x-show="sidebarCollapsed" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        class="lg:block hidden mb-4"> <button @click="sidebarCollapsed = false"
                            class="bg-primary-600 text-white p-3 rounded-lg shadow-lg hover:bg-primary-700 transition-colors flex items-center">
                            <x-heroicon-o-bars-3 class="w-5 h-5 mr-2" />
                            {{ __('filament-docs::docs.navigation.show_menu') }}
                        </button>
                    </div>
                    @if (empty($sections))
                        <!-- No Documentation Available -->
                        <div
                            class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                <x-heroicon-o-book-open class="w-12 h-12 text-gray-400 dark:text-gray-500" />
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3">
                                {{ __('filament-docs::docs.empty.title') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                {{ __('filament-docs::docs.empty.description') }}</p>
                        </div>
                    @elseif(!empty($this->searchQuery) && empty($searchResults))
                        <!-- No Search Results -->
                        <div
                            class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                <x-heroicon-o-magnifying-glass class="w-12 h-12 text-gray-400 dark:text-gray-500" />
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3">
                                {{ __('filament-docs::docs.search.no_results_title') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                {{ __('filament-docs::docs.search.no_results_description', ['query' => $this->searchQuery]) }}
                            </p>
                            <button wire:click="clearSearch"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors duration-200">
                                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                                {{ __('filament-docs::docs.search.back_to_sections') }}
                            </button>
                        </div>
                    @elseif(!empty($this->searchQuery) && !empty($searchResults))
                        <!-- Search Results Display -->
                        <div class="space-y-6">
                            <div
                                class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ __('filament-docs::docs.search.results_title', ['query' => $this->searchQuery]) }}
                                    </h2>
                                    <button wire:click="clearSearch"
                                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        {{ __('filament-docs::docs.search.clear') }}
                                    </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    {{ trans_choice('filament-docs::docs.search.sections_found', count($searchResults), ['count' => count($searchResults)]) }}
                                </p>

                                @foreach ($searchResults as $result)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-4 hover:border-primary-300 dark:hover:border-primary-600 transition-colors">
                                        <div class="flex items-start justify-between mb-3">
                                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                                {!! $this->highlightSearchTerm($result['section']['title'], $this->searchQuery) !!}
                                            </h3>
                                            <button wire:click="selectSection('{{ $result['section']['id'] }}')"
                                                class="text-sm bg-primary-600 text-white px-3 py-1 rounded-lg hover:bg-primary-700 transition-colors">
                                                {{ __('filament-docs::docs.actions.view_section') }}
                                            </button>
                                        </div>

                                        @if ($result['total_matches'] > 0)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                {{ trans_choice('filament-docs::docs.search.matches_in_section', $result['total_matches'], ['count' => $result['total_matches']]) }}
                                            </p>

                                            @foreach ($result['matches'] as $match)
                                                <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg mb-2 text-sm">
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400">{{ __('filament-docs::docs.search.line', ['line' => $match['line']]) }}</span>
                                                    <div class="mt-1">{!! $match['highlighted'] !!}</div>
                                                </div>
                                            @endforeach

                                            @if ($result['total_matches'] > 3)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                    {{ __('filament-docs::docs.search.more_matches', ['count' => $result['total_matches'] - 3]) }}
                                                    <button
                                                        wire:click="selectSection('{{ $result['section']['id'] }}')"
                                                        class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                                        {{ __('filament-docs::docs.actions.view_full_section') }}
                                                    </button>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($currentSection)
                        <!-- Selected Section Content -->
                        <div
                            class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 block text-gray-900 dark:text-white">
                            <!-- Section Header -->
                            <div
                                class="bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 p-4 border-b border-gray-200 dark:border-gray-700 relative">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                            @php
                                                $currentIndex = 0;
                                                foreach ($sections as $index => $section) {
                                                    if ($section['id'] === $this->selectedSection) {
                                                        $currentIndex = $index + 1;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            {{ $currentIndex }}
                                        </div>
                                        <div>
                                            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                                {{ $currentSection['title'] }}</h1>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                                @php
                                                    $currentIndex = 0;
                                                    foreach ($sections as $index => $section) {
                                                        if ($section['id'] === $this->selectedSection) {
                                                            $currentIndex = $index + 1;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                {{ __('filament-docs::docs.section.progress', [
                                                    'current' => $currentIndex,
                                                    'total' => count($sections),
                                                ]) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- Section Content -->
                            <div class="p-6 transition-opacity duration-200" wire:loading.class="opacity-50"
                                wire:target="selectSection">
                                <div class="prose prose-blue prose-sm max-w-none" x-init="// Wrap tables in containers for better responsive handling
                                $nextTick(() => {
                                    $el.querySelectorAll('table').forEach(table => {
                                        if (!table.parentElement.classList.contains('table-container')) {
                                            const container = document.createElement('div');
                                            container.className = 'table-container';
                                            table.parentNode.insertBefore(container, table);
                                            container.appendChild(table);
                                        }
                                    });
                                });">
                                    {!! $currentSection['html'] !!}
                                </div>
                            </div><!-- Section Navigation -->
                            <div class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        @php
                                            $currentIndex = 0;
                                            foreach ($sections as $index => $section) {
                                                if ($section['id'] === $this->selectedSection) {
                                                    $currentIndex = $index;
                                                    break;
                                                }
                                            }
                                            $prevSection = $currentIndex > 0 ? $sections[$currentIndex - 1] : null;
                                        @endphp
                                        @if ($prevSection)
                                            <button wire:click="selectSection('{{ $prevSection['id'] }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                                <x-heroicon-o-arrow-left class="w-3 h-3 mr-1.5" />
                                                <div class="text-left">
                                                    <div class="text-xs text-gray-500">
                                                        {{ __('filament-docs::docs.navigation.previous') }}</div>
                                                    <div class="font-medium text-sm">{{ $prevSection['title'] }}</div>
                                                </div>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $currentIndex + 1 }} / {{ count($sections) }}
                                        </div>
                                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                            <div class="bg-primary-600 h-1.5 rounded-full"
                                                style="width: {{ (($currentIndex + 1) / count($sections)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        @php
                                            $nextSection =
                                                $currentIndex < count($sections) - 1
                                                    ? $sections[$currentIndex + 1]
                                                    : null;
                                        @endphp
                                        @if ($nextSection)
                                            <button wire:click="selectSection('{{ $nextSection['id'] }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                                <div class="text-right">
                                                    <div class="text-xs text-gray-500">
                                                        {{ __('filament-docs::docs.navigation.next') }}</div>
                                                    <div class="font-medium text-sm">{{ $nextSection['title'] }}</div>
                                                </div>
                                                <x-heroicon-o-arrow-right class="w-3 h-3 ml-1.5" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
</x-filament-panels::page>
