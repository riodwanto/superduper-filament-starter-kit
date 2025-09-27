<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Documentation Path
    |--------------------------------------------------------------------------
    |
    | This option defines the default path where markdown documentation
    | files will be stored. You can override this in your DocsPage
    | implementation by overriding the getDocsPath() method.
    |
    */

    'default_docs_path' => resource_path('docs'),    /*
    |--------------------------------------------------------------------------
    | Markdown Parser Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the CommonMark markdown parser.
    | These settings will be passed to the CommonMarkConverter.
    |
    */

    'markdown' => [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
        'max_nesting_level' => 10,
        'slug_normalizer' => [
            'max_length' => 255,
        ],
        
        /*
        |--------------------------------------------------------------------------
        | CommonMark Extensions
        |--------------------------------------------------------------------------
        |
        | Enable or disable CommonMark extensions. Set to true to enable,
        | false to disable, or provide configuration array for extensions
        | that support configuration options.
        |
        */
        'extensions' => [
            // Core extensions
            'commonmark_core' => true,
            
            // Table extension for GitHub-style tables
            'table' => true,
            
            // Strikethrough extension for ~~text~~
            'strikethrough' => true,
            
            // Autolink extension for automatic URL detection
            'autolink' => true,
            
            // Task list extension for - [x] checkboxes
            'task_list' => true,
            
            // Disallow certain raw HTML for security
            'disallowed_raw_html' => [
                'disallowed_tags' => ['script', 'iframe', 'object', 'embed', 'form'],
            ],
            
            // Attributes extension for adding HTML attributes
            'attributes' => false,
            
            // Footnote extension
            'footnote' => true,
            
            // Description list extension
            'description_list' => true,
            
            // External link extension
            'external_link' => [
                'internal_hosts' => ['localhost'],
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => 'external',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
            
            // Table of contents extension
            'table_of_contents' => [
                'html_class' => 'table-of-contents',
                'position' => 'top',
                'style' => 'bullet',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'normalize' => 'relative',
                'placeholder' => null,
            ],
            
            // Smart punctuation
            'smart_punct' => true,
            
            // Heading permalink extension
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => '',
                'apply_id_to_heading' => true,
                'heading_class' => '',
                'fragment_prefix' => '',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the search functionality.
    |
    */

    'search' => [
        'debounce_ms' => 200,
        'max_results_per_section' => 3,
        'highlight_class' => 'bg-yellow-200 text-yellow-800 px-1 rounded',
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the user interface.
    |
    */

    'ui' => [
        'sidebar_width' => 'lg:w-80',
        'max_sidebar_height' => 'h-full',
        'loading_delay_ms' => 200,
        'default_navigation_icon' => 'heroicon-o-book-open',
        'default_navigation_group' => 'Documentations',
    ],

    /*
    |--------------------------------------------------------------------------
    | Section Ordering
    |--------------------------------------------------------------------------
    |
    | Default ordering for documentation sections. You can override this
    | in your DocsPage implementation by overriding the getSectionOrder() method.
    |
    */

    'section_order' => [
        'getting-started' => 1,
        'installation' => 2,
        'configuration' => 3,
        'usage' => 4,
        'examples' => 5,
        'api' => 6,
        'api-reference' => 7,
        'troubleshooting' => 8,
        'faq' => 9,
        'changelog' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Extensions
    |--------------------------------------------------------------------------
    |
    | Supported file extensions for documentation files.
    |
    */

    'supported_extensions' => ['md', 'markdown'],

    /*
    |--------------------------------------------------------------------------
    | Commands Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the package commands.
    |
    */

    'commands' => [
        'make_docs_page' => [
            'default_panel' => 'admin',
            'default_navigation_group' => 'Documentations',
            'default_navigation_icon' => 'heroicon-o-book-open',
        ],
        'make_markdown' => [
            'templates' => ['basic', 'guide', 'api', 'troubleshooting', 'feature'],
            'default_template' => 'basic',
        ],
    ],

    'localization' => [
        'supported_locales' => ['en', 'es', 'fr'],
        'locale_paths' => [
            'en' => 'docs/en',
            'es' => 'docs/es',
            'fr' => 'docs/fr',
        ],
    ],

];
