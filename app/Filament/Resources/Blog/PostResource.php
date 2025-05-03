<?php

namespace App\Filament\Resources\Blog;

use App\Enums\Blog\PostStatus;
use App\Filament\Resources\Blog\PostResource\Pages;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'fluentui-news-20';

    protected static ?int $navigationSort = -2;

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
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('editSlug')
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
                                            })
                                    ),

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
                                    ->options(PostStatus::class)
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
                                    }),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Publication Date')
                                    ->required(fn(Get $get): bool => $get('status') === PostStatus::PUBLISHED->value)
                                    ->visible(fn(Get $get): bool => $get('status') === PostStatus::PUBLISHED->value)
                                    ->placeholder('Select publication date')
                                    ->helperText('Date when the post will be published')
                                    ->default(now()),

                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('Schedule For')
                                    ->required(fn(Get $get): bool => $get('status') === PostStatus::PENDING->value)
                                    ->visible(fn(Get $get): bool => $get('status') === PostStatus::PENDING->value)
                                    ->placeholder('Select scheduled date')
                                    ->seconds(false)
                                    ->timezone('UTC')
                                    ->hint('Post will be automatically published at this time')
                                    ->hintIcon('heroicon-m-clock'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Post')
                                    ->helperText('Featured posts appear prominently on the site')
                                    ->default(false)
                                    ->required(),
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
                                    ->required(),

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
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->description('Search Engine Optimization')
                            ->icon('heroicon-o-magnifying-glass')
                            ->collapsed()
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
            ->columns([
                SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Image')
                    ->collection('featured')
                    ->defaultImageUrl(fn(Post $record) => $record->getFeaturedImageUrl('thumbnail') ?? 'https://placehold.co/150x150/webp'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
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
                    ->toggleable(isToggledHiddenByDefault: false),

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
                    ->preload(),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Posts')
                    ->query(fn(Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\Filter::make('published')
                    ->label('Published Posts')
                    ->query(fn(Builder $query): Builder => $query->published()),

                Tables\Filters\Filter::make('published_at')
                    ->label('Published This Month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('published_at', now()->month)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Post $record) {
                            $duplicate = $record->replicate();
                            $duplicate->title = "Copy of " . $record->title;
                            $duplicate->slug = Str::slug($duplicate->title);
                            $duplicate->status = 'draft';
                            $duplicate->published_at = null;
                            $duplicate->view_count = 0;
                            $duplicate->comments_count = 0;
                            $duplicate->save();

                            // Copy tags
                            $duplicate->syncTags($record->tags);

                            // Copy media
                            foreach ($record->getMedia('featured') as $media) {
                                $media->copy($duplicate, 'featured');
                            }

                            foreach ($record->getMedia('gallery') as $media) {
                                $media->copy($duplicate, 'gallery');
                            }

                            return redirect()->route('filament.admin.resources.blog.posts.edit', $duplicate->id);
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publishSelected')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Post $records): void {
                            foreach ($records as $record) {
                                $record->status = 'published';
                                $record->published_at = now();
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
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
        return static::getModel()::count();
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
            'Status' => $record->published_at?->isPast() ? 'Published' : 'Draft',
        ];
    }
}
