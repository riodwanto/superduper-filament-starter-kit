<?php

namespace App\Filament\Clusters\SitesPages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;
use Riodwanto\FilamentAceEditor\AceEditor;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;

class HomePage extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static ?string $cluster = \App\Filament\Clusters\SitesPages::class;

    protected static ?string $navigationIcon = '';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.edit-page';

    public string $fileContent = '';
    public int $formKey = 0;

    // TODO: Add permission update
    // public static function getPermissionPrefixes(): array
    // {
    //     return [
    //     ];
    // }

    public function mount(): void
    {
        // Check if user has permission to view this page using Shield
        if (! auth()->user()->can('page_HomePage')) {
            abort(403, 'You do not have permission to access this page.');
        }

        $this->loadFileContent();
    }

    /**
     * Load file content with error handling
     */
    protected function loadFileContent(): void
    {
        try {
            $mainFilePath = $this->getMainFilePath();

            if (File::exists($mainFilePath)) {
                $content = File::get($mainFilePath);
                $this->fileContent = $content ?: '';
            } else {
                $this->fileContent = $this->getDefaultContent();
                Log::warning("Home page file not found: {$mainFilePath}");
            }
        } catch (Exception $e) {
            Log::error('Error loading home page content: ' . $e->getMessage());
            $this->fileContent = '';

            Notification::make()
                ->title('Error loading content')
                ->body('There was an issue loading the page content. Please try again.')
                ->danger()
                ->send();
        }
    }

    /**
     * Get the relative file path for display
     */
    public function getDisplayFilePath(): string
    {
        $fullPath = $this->getMainFilePath();
        // Remove the base path to show relative path
        return str_replace(base_path() . '/', '', $fullPath);
    }

    /**
     * Save file content with validation and security checks
     */
    public function save(): void
    {
        // TODO: Check permission update
        // if (! auth()->user()->can('page_HomePage')) {
        //     Notification::make()
        //         ->title('Permission Denied')
        //         ->body('You do not have permission to update this page.')
        //         ->danger()
        //         ->send();
        //     return;
        // }

        try {
            // Validate the form data
            $this->form->validate();

            // TODO: Add content validation
            // $this->validateContent();

            // Save the file
            $this->saveFile();

            // Success notification
            Notification::make()
                ->title('Content Saved Successfully')
                ->body('The home page content has been updated.')
                ->success()
                ->send();

            // Log the action
            Log::info('Home page content updated by user: ' . auth()->user()->email);

        } catch (ValidationException $e) {
            // Handle validation errors
            Notification::make()
                ->title('Validation Error')
                ->body('Please check your content and try again.')
                ->danger()
                ->send();

            throw $e;

        } catch (Exception $e) {
            Log::error('Error saving home page content: ' . $e->getMessage());

            Notification::make()
                ->title('Save Error')
                ->body('There was an error saving your content. Please try again.')
                ->danger()
                ->send();
        }
    }

    /**
     * Validate content for security issues
     */
    protected function validateContent(): void
    {
        $content = $this->fileContent;

        // Check for potentially dangerous content
        $dangerousPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i', // onclick, onload, etc.
            '/<iframe[^>]*>/i',
            '/<object[^>]*>/i',
            '/<embed[^>]*>/i',
            '/<?php/i',
            '/<%/i', // ASP tags
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw ValidationException::withMessages([
                    'fileContent' => 'Content contains potentially unsafe elements. Please remove scripts, PHP code, or dangerous HTML elements.'
                ]);
            }
        }

        // Validate HTML structure (basic check)
        if (!empty($content)) {
            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();
            $doc->loadHTML('<!DOCTYPE html><html><body>' . $content . '</body></html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $errors = libxml_get_errors();

            if (!empty($errors)) {
                $errorMessages = array_map(fn($error) => trim($error->message), $errors);
                throw ValidationException::withMessages([
                    'fileContent' => 'HTML validation errors: ' . implode(', ', array_unique($errorMessages))
                ]);
            }
            libxml_clear_errors();
        }
    }

    /**
     * Save file with proper error handling
     */
    protected function saveFile(): void
    {
        $mainFilePath = $this->getMainFilePath();

        // Ensure directory exists
        $directory = dirname($mainFilePath);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Create backup if file exists
        if (File::exists($mainFilePath)) {
            $backupPath = $mainFilePath . '.backup.' . now()->format('Y-m-d_H-i-s');
            File::copy($mainFilePath, $backupPath);
        }

        // Write the file
        if (!File::put($mainFilePath, $this->fileContent)) {
            throw new Exception('Failed to write file to disk');
        }

        // Verify the file was written correctly
        if (!File::exists($mainFilePath) || File::get($mainFilePath) !== $this->fileContent) {
            throw new Exception('File verification failed after writing');
        }
    }

    /**
     * Get the main file path
     */
    protected function getMainFilePath(): string
    {
        return resource_path('views/components/superduper/pages/home.blade.php');
    }

    /**
     * Get default content for new files
     */
    protected function getDefaultContent(): string
    {
        return <<<'HTML'
            <div class="container px-4 py-8 mx-auto">
                <div class="text-center">
                    <h1 class="mb-4 text-4xl font-bold">Welcome to Your Site</h1>
                    <p class="text-lg text-gray-600">This is your home page content. Edit this content from the admin panel.</p>
                </div>
            </div>
        HTML;
    }

    /**
     * Define the form schema
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                AceEditor::make('fileContent')
                    ->label('Home Page Content')
                    ->hiddenLabel()
                    ->mode('html')
                    ->height('768px')
                    // ->helperText('Edit the home page content. HTML is allowed, but scripts and PHP code are not permitted for security reasons.')
                    ->required()
                    ->rules([
                        'string',
                        'max:1048576', // 1MB limit
                    ])
                    ->validationMessages([
                        'required' => 'Content cannot be empty',
                        'max' => 'Content is too large (maximum 1MB)',
                    ])
            ])
            ->statePath('');
    }

    /**
     * Navigation configuration
     */
    public static function getNavigationGroup(): ?string
    {
        return 'Main';
    }

    public static function getNavigationLabel(): string
    {
        return 'Home';
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    /**
     * Check if user can access this page
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_HomePage') ?? false;
    }

    /**
     * Get the page title
     */
    public function getTitle(): string
    {
        return 'Edit Home Page';
    }

    /**
     * Get the page heading
     */
    public function getHeading(): string
    {
        return 'Home Page Editor';
    }

    /**
     * Get the subheading
     */
    public function getSubheading(): ?string
    {
        return 'Manage your website\'s home page content.';
    }

    /**
     * Format bytes to human readable format
     */
    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}