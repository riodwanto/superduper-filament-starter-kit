<?php

namespace App\Filament\Resources\Blog;

use App\Enums\Blog\PostStatus;
use App\Filament\Resources\Blog\PostResource\Pages;
use App\Models\Blog\Post;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
// use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'fluentui-news-20';

    protected static ?int $navigationSort = -2;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'publish',
            'archive',
            'feature',
            'change_author',
            'approve',
            'schedule',
            'manage_seo',
            'bulk_publish',
            'view_analytics'
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Post Content')
                            ->description('The main content of your blog post')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->maxLength(255)
                                    ->placeholder('Enter post title')
                                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Post::class, 'slug', ignoreRecord: true)
                                    ->helperText('URL-friendly version of the title - generated automatically')
                                    ->suffixAction(function (string $operation) {
                                        if ($operation === 'edit') {
                                            return Forms\Components\Actions\Action::make('editSlug')
                                                ->icon('heroicon-o-pencil-square')
                                                ->modalHeading('Edit Slug')
                                                ->modalDescription('Customize the URL slug for this post. Use lowercase letters, numbers, and hyphens only.')
                                                ->modalIcon('heroicon-o-link')
                                                ->modalSubmitActionLabel('Update Slug')
                                                ->form([
                                                    Forms\Components\TextInput::make('new_slug')
                                                        ->hiddenLabel()
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->live(debounce: 500)
                                                        ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                            $set('new_slug', Str::slug($state));
                                                        })
                                                        ->unique(Post::class, 'slug', ignoreRecord: true)
                                                        ->helperText('The slug will be automatically formatted as you type.')
                                                ])
                                                ->action(function (array $data, Forms\Set $set) {
                                                    $set('slug', $data['new_slug']);

                                                    Notification::make()
                                                        ->title('Slug updated')
                                                        ->success()
                                                        ->send();
                                                });
                                        }
                                        return null;
                                    }),

                                Forms\Components\Textarea::make('content_overview')
                                    ->required()
                                    ->placeholder('Provide a brief summary or excerpt of this post')
                                    ->helperText('This will appear on the blog listing page')
                                    ->rows(5),

                                Forms\Components\RichEditor::make('content_raw')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h1',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->required()
                                    ->placeholder('Write your post content here...')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('blog/posts/content-uploads')
                                    ->columnSpanFull()
                                    ->maxLength(65535)
                                    ->helperText('Format your content using the toolbar above')
                                    ->hint(function (Get $get): string {
                                        $wordCount = str_word_count(strip_tags($get('content_raw')));
                                        $readingTime = ceil($wordCount / 200); // Assuming 200 words per minute
                                        return "{$wordCount} words | ~{$readingTime} min read";
                                    })
                                    ->extraInputAttributes(['style' => 'min-height: 500px;']),
                            ]),

                        Forms\Components\Section::make('Media')
                            ->description('Visual elements for your post')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('featured')
                                    ->label('Featured Image')
                                    ->collection('featured')
                                    ->image()
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675')
                                    ->helperText('This image will be displayed prominently in post listings and social shares (16:9 ratio recommended)')
                                    ->downloadable()
                                    ->responsiveImages(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Visibility')
                            ->description('Control how this post appears')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options(function (?Post $record) {
                                        $user = Auth::user();
                                        $currentStatus = $record?->status;

                                        $allowedStatuses = [];

                                        if ($user && $user->isSuperAdmin()) {
                                            $allowedStatuses = PostStatus::class;
                                        } elseif ($user && $user->hasAnyRole(['admin', 'editor'])) {
                                            $allowedStatuses = PostStatus::class;
                                        } elseif ($user && $user->hasRole('author')) {
                                            $allowedStatuses = [
                                                PostStatus::DRAFT->value => PostStatus::DRAFT->getLabel(),
                                                PostStatus::PENDING->value => PostStatus::PENDING->getLabel(),
                                            ];

                                            if ($currentStatus === PostStatus::PUBLISHED) {
                                                $allowedStatuses[PostStatus::PUBLISHED->value] = PostStatus::PUBLISHED->getLabel();
                                            }
                                        }

                                        return $allowedStatuses;
                                    })
                                    ->default(PostStatus::DRAFT->value)
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state === PostStatus::PUBLISHED->value && !$get('published_at')) {
                                            $set('published_at', now());
                                        } elseif ($state === PostStatus::DRAFT->value) {
                                            $set('published_at', null);
                                            $set('scheduled_at', null);
                                        }
                                    })
                                    ->helperText(function () {
                                        $user = Auth::user();
                                        if ($user && $user->hasRole('author')) {
                                            return 'Authors can create drafts or submit for review. Only editors can publish.';
                                        }
                                        return 'Control the publication status of this post.';
                                    }),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Publication Date')
                                    ->required(fn(Get $get): bool => $get('status') === PostStatus::PUBLISHED->value)
                                    ->placeholder('Select publication date')
                                    ->helperText('Date when the post will be published')
                                    ->default(now())
                                    ->disabled(function () {
                                        $user = Auth::user();
                                        return $user && $user->hasRole('author');
                                    }),

                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('Schedule For')
                                    ->required(fn(Get $get): bool => $get('status') === PostStatus::PENDING->value)
                                    ->visible(fn(Get $get): bool => $get('status') === PostStatus::PENDING->value)
                                    ->placeholder('Select scheduled date')
                                    ->seconds(false)
                                    ->timezone('UTC')
                                    ->hint('Post will be automatically published at this time')
                                    ->hintIcon('heroicon-m-clock')
                                    ->disabled(function (?Post $record) {
                                        $user = Auth::user();
                                        return $user && $user->hasRole('author') && !$user->can('schedule', $record ?? new Post());
                                    }),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Post')
                                    ->helperText('Featured posts appear prominently on the site')
                                    ->default(false)
                                    ->visible(function (?Post $record) {
                                        $user = Auth::user();
                                        return $user && $user->can('feature', $record ?? new Post());
                                    })
                                    ->disabled(function (?Post $record) {
                                        $user = Auth::user();
                                        return !$user || !$user->can('feature', $record ?? new Post());
                                    }),

                                Forms\Components\Placeholder::make('analytics')
                                    ->label('Post Analytics')
                                    ->content(function (?Post $record): HtmlString {
                                        if (!$record) {
                                            return new HtmlString('<span class="text-sm text-gray-500">Analytics will be available after saving</span>');
                                        }

                                        return new HtmlString("
                                            <div class='space-y-2'>
                                                <div class='flex justify-between'>
                                                    <span class='text-sm text-gray-600'>Views:</span>
                                                    <span class='text-sm font-semibold'>{$record->view_count}</span>
                                                </div>
                                                <div class='flex justify-between'>
                                                    <span class='text-sm text-gray-600'>Reading Time:</span>
                                                    <span class='text-sm font-semibold'>{$record->reading_time} min</span>
                                                </div>
                                                <div class='flex justify-between'>
                                                    <span class='text-sm text-gray-600'>Comments:</span>
                                                    <span class='text-sm font-semibold'>{$record->comments_count}</span>
                                                </div>
                                            </div>
                                        ");
                                    })
                                    ->visible(function (?Post $record) {
                                        $user = Auth::user();
                                        return $record && $user && $user->can('viewAnalytics', $record);
                                    }),
                            ]),

                        Forms\Components\Section::make('Categorization')
                            ->description('Organize and classify this post')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Components\Select::make('blog_category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->required(),

                                SpatieTagsInput::make('tags')
                                    ->label('Tags')
                                    ->placeholder('Add tags')
                                    ->helperText('Comma-separated tags to help with search and filtering'),
                            ]),

                        Forms\Components\Section::make('Attribution')
                            ->description('Who created this post')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Select::make('blog_author_id')
                                    ->label('Author')
                                    ->relationship(
                                        name: 'author',
                                        modifyQueryUsing: fn(Builder $query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'author'),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->firstname} {$record->lastname}")
                                    ->searchable(['firstname', 'lastname'])
                                    ->preload()
                                    ->required()
                                    ->disabled(function (?Post $record) {
                                        $user = Auth::user();
                                        if (!$user) return true;

                                        if ($user->isSuperAdmin()) {
                                            return false;
                                        }

                                        if (!$record) {
                                            return !$user->can('change_author_blog::post');
                                        }

                                        return !$user->can('changeAuthor', $record);
                                    })
                                    ->helperText(function (?Post $record) {
                                        $user = Auth::user();
                                        if (!$user) return '';

                                        if ($user->isSuperAdmin()) {
                                            return 'Super Admin can change any post author.';
                                        }

                                        if (!$user->can('change_author_blog::post')) {
                                            return 'Only administrators can change the post author.';
                                        }
                                        return 'Select the author for this post.';
                                    }),

                                Forms\Components\Placeholder::make('audit_trail')
                                    ->label('')
                                    ->content(function (Post $record): HtmlString {
                                        if ($record->exists) {
                                            $creatorName = $record->creator ? "{$record->creator->firstname} {$record->creator->lastname}" : 'Unknown';
                                            $updaterName = $record->updater ? "{$record->updater->firstname} {$record->updater->lastname}" : 'Unknown';
                                            $createdAt = $record->created_at?->format('M d, Y \a\t h:ia');
                                            $updatedAt = $record->updated_at?->diffForHumans();

                                            return new HtmlString("
                                                <div class='space-y-4'>
                                                    <div>
                                                        <div class='text-sm font-medium text-gray-400 dark:text-gray-400'>Created by</div>
                                                        <div class='flex items-center space-x-2'>
                                                            <span class='text-sm font-bold text-primary-600 dark:text-primary-400'>{$creatorName}</span>
                                                            <span class='text-xs text-gray-500 dark:text-gray-400'>on {$createdAt}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class='text-sm font-medium text-gray-400 dark:text-gray-400'>Last updated by</div>
                                                        <div class='flex items-center space-x-2'>
                                                            <span class='text-sm font-bold text-primary-600 dark:text-primary-400'>{$updaterName}</span>
                                                            <span class='text-xs text-gray-500 dark:text-gray-400'>{$updatedAt}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            ");
                                        }

                                        return new HtmlString("<span class='text-sm text-gray-500 dark:text-gray-400'>Audit information will be available after saving</span>");
                                    })
                                    ->visible(fn(string $operation): bool => $operation === 'edit'),
                            ])
                            ->visible(function (?Post $record) {
                                return Auth::user()->can('change_author', $record);
                            }),

                        Forms\Components\Section::make('SEO')
                            ->description('Search Engine Optimization')
                            ->icon('heroicon-o-magnifying-glass')
                            ->collapsed()
                            ->visible(function (?Post $record) {
                                $user = Auth::user();
                                return $user && $user->can('manageSeo', $record ?? new Post());
                            })
                            ->schema([
                                Forms\Components\Textarea::make('meta_title')
                                    ->placeholder('Leave empty to use post title')
                                    ->maxLength(70)
                                    ->helperText('Recommended: 50-60 characters')
                                    ->rows(2),

                                Forms\Components\Textarea::make('meta_description')
                                    ->placeholder('Leave empty to use post overview')
                                    ->maxLength(160)
                                    ->helperText('Recommended: 150-160 characters')
                                    ->rows(5),

                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('seo_preview')
                                            ->label('Google Preview')
                                            ->content(function (Get $get): HtmlString {
                                                $title = $get('meta_title') ?: $get('title');
                                                $description = $get('meta_description') ?: $get('content_overview');
                                                $url = config('app.url') . '/blog/' . ($get('slug') ?: Str::slug($get('title')));

                                                return new HtmlString("
                                                    <div class='text-base font-medium text-primary-600'>{$title}</div>
                                                    <div class='text-xs text-emerald-600'>{$url}</div>
                                                    <div class='mt-1 text-sm text-gray-600'>{$description}</div>
                                                ");
                                            }),
                                    ])
                                    ->compact(),

                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('generateSeoMetadata')
                                        ->label('Generate SEO Metadata')
                                        ->icon('heroicon-m-sparkles')
                                        ->action(function (Get $get, Set $set) {
                                            $title = $get('title');
                                            $overview = $get('content_overview');

                                            // Generate meta title (up to 60 chars)
                                            $set('meta_title', Str::limit($title, 60));

                                            // Generate meta description (up to 155 chars)
                                            if ($overview) {
                                                $set('meta_description', Str::limit($overview, 155));
                                            }

                                            Notification::make()
                                                ->title('SEO metadata generated')
                                                ->success()
                                                ->send();
                                        }),
                                ])->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                // Authors can only see their own posts
                if ($user && $user->hasRole('author')) {
                    $query->where(function ($q) use ($user) {
                        $q->where('blog_author_id', $user->id)
                          ->orWhere('created_by', $user->id);
                    });
                }

                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(function () {
                        $user = Auth::user();
                        return $user && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
                    }),

                Tables\Columns\TextColumn::make('author.firstname')
                    ->label('Author')
                    ->formatStateUsing(fn(Model $record) => "{$record->author->firstname} {$record->author->lastname}")
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('reading_time')
                    ->label('Reading')
                    ->suffix(' min')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(function () {
                        $user = auth()->user();
                        return $user->can('view_analytics_blog::post');
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last update')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn(Post $record) => match ($record->is_featured) {
                true => '!border-x-2 !border-x-success-600 dark:!border-x-success-300',
                default => '',
            })
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PostStatus::class),

                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('blog_author_id')
                    ->label('Author')
                    ->relationship('author', 'firstname')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->firstname} {$record->lastname}")
                    ->searchable()
                    ->preload()
                    ->visible(fn() => auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor'])),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Posts')
                    ->query(fn(Builder $query): Builder => $query->where('is_featured', true))
                    ->visible(fn() => auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor'])),

                Tables\Filters\Filter::make('published')
                    ->label('Published Posts')
                    ->query(fn(Builder $query): Builder => $query->published()),

                Tables\Filters\Filter::make('published_at')
                    ->label('Published This Month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('published_at', now()->month)),

                Tables\Filters\Filter::make('pending_approval')
                    ->label('Pending Approval')
                    ->query(fn(Builder $query): Builder => $query->where('status', PostStatus::PENDING))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make()
                        ->visible(fn(Post $record) => auth()->user()->can('update', $record)),

                    Tables\Actions\Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Post $record) {
                            $record->update([
                                'status' => PostStatus::PUBLISHED,
                                'published_at' => now(),
                                'last_published_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Post published successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn(Post $record) =>
                            auth()->user()->can('publish', $record) &&
                            $record->status !== PostStatus::PUBLISHED
                        ),

                    Tables\Actions\Action::make('feature')
                        ->label($fn = fn(Post $record) => $record->is_featured ? 'Unfeature' : 'Feature')
                        ->icon($fn = fn(Post $record) => $record->is_featured ? 'heroicon-o-star' : 'heroicon-o-star')
                        ->color($fn = fn(Post $record) => $record->is_featured ? 'warning' : 'success')
                        ->action(function (Post $record) {
                            $record->update(['is_featured' => !$record->is_featured]);

                            Notification::make()
                                ->title($record->is_featured ? 'Post featured' : 'Post unfeatured')
                                ->success()
                                ->send();
                        })
                        ->visible(fn(Post $record) => auth()->user()->can('feature', $record)),

                    Tables\Actions\Action::make('duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Post $record) {
                            $user = auth()->user();

                            $duplicate = $record->replicate();
                            $duplicate->title = "Copy of " . $record->title;
                            $duplicate->slug = Str::slug($duplicate->title);
                            $duplicate->status = PostStatus::DRAFT;
                            $duplicate->published_at = null;
                            $duplicate->view_count = 0;
                            $duplicate->comments_count = 0;
                            $duplicate->is_featured = false;

                            if ($user->hasRole('author')) {
                                $duplicate->blog_author_id = $user->id;
                            }

                            $duplicate->save();

                            // Copy tags
                            $duplicate->syncTags($record->tags);

                            // Copy media
                            foreach ($record->getMedia('featured') as $media) {
                                $media->copy($duplicate, 'featured');
                            }

                            return redirect()->route('filament.admin.resources.blog.posts.edit', $duplicate->id);
                        }),

                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->action(function (Post $record) {
                            $record->update([
                                'status' => PostStatus::PUBLISHED,
                                'published_at' => now(),
                                'last_published_at' => now(),
                            ]);

                            // Notify the author if available
                            if ($record->author) {
                                Notification::make()
                                    ->title('Your post has been approved and published!')
                                    ->body('The post "' . $record->title . '" is now live.')
                                    ->success()
                                    ->sendToDatabase($record->author);
                            }

                            Notification::make()
                                ->title('Post approved and published successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn(Post $record) =>
                            auth()->user()->can('approve', $record) &&
                            $record->status === PostStatus::PENDING
                        ),

                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(Post $record) => auth()->user()->can('delete', $record)),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->can('deleteAny', Post::class)),

                    Tables\Actions\BulkAction::make('publishSelected')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                if (auth()->user()->can('publish', $record)) {
                                    $record->update([
                                        'status' => PostStatus::PUBLISHED,
                                        'published_at' => now(),
                                        'last_published_at' => now(),
                                    ]);
                                }
                            }

                            Notification::make()
                                ->title('Selected posts published successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn() => auth()->user()->can('publish', Post::class)),

                    Tables\Actions\BulkAction::make('featureSelected')
                        ->label('Feature Selected')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                if (auth()->user()->can('feature', $record)) {
                                    $record->update(['is_featured' => true]);
                                }
                            }

                            Notification::make()
                                ->title('Selected posts featured successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn() => auth()->user()->can('feature', Post::class)),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Authors can only see their own posts
        if ($user && $user->hasRole('author')) {
            $query->where(function ($q) use ($user) {
                $q->where('blog_author_id', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.blog");
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user && $user->hasRole('author')) {
            // Authors see count of their own posts
            return (string) static::getModel()::where('blog_author_id', $user->id)
                ->orWhere('created_by', $user->id)
                ->count();
        }

        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Category' => $record->category->name,
            'Author' => "{$record->author->firstname} {$record->author->lastname}",
            'Status' => $record->status->getLabel(),
        ];
    }
}
