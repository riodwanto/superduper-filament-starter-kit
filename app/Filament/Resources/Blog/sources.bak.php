<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\PostResource\Pages;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TomatoPHP\FilamentMediaManager\Form\MediaManagerInput;

class PostResourcessssss extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'blog/posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'fluentui-news-20';

    protected static ?int $navigationSort = -2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Post')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Forms\Components\Section::make('Image')
                                    ->schema([
                                        MediaManagerInput::make('featured')
                                            ->label('Featured Image')
                                            // ->collection('featured')
                                            // ->maxFiles(1)
                                            ->hiddenLabel()
                                            ->schema([])
                                            ->defaultItems(1)
                                            ->minItems(1),
                                    ])
                                    ->collapsible(),

                                Forms\Components\Section::make('Post Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->maxLength(255)
                                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) =>
                                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Post::class, 'slug', fn($record) => $record)
                                            ->helperText('This is used in URLs and should be unique.'),

                                        Forms\Components\MarkdownEditor::make('content_raw')
                                            ->label('Content')
                                            ->required()
                                            ->columnSpan('full'),

                                        Forms\Components\Textarea::make('content_overview')
                                            ->label('Content Overview')
                                            ->helperText('A brief summary of the post content. Will be auto-generated if left empty.')
                                            ->nullable()
                                            ->maxLength(500)
                                            ->columnSpan('full'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Meta & Publishing')
                            ->schema([
                                Forms\Components\Section::make('Categories & Tags')
                                    ->schema([
                                        Forms\Components\Select::make('blog_author_id')
                                            ->label('Author')
                                            ->relationship(
                                                name: 'author',
                                                modifyQueryUsing: fn(Builder $query) => $query->with('roles'),
                                            )
                                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->firstname} {$record->lastname}")
                                            ->searchable(['firstname', 'lastname'])
                                            ->required(),

                                        Forms\Components\Select::make('blog_category_id')
                                            ->label('Category')
                                            ->relationship('category', 'name')
                                            ->options(Category::active()->pluck('name', 'id'))
                                            ->preload()
                                            ->searchable()
                                            ->required(),

                                        SpatieTagsInput::make('tags')
                                            ->helperText('Separate with comma or press Enter')
                                            ->columnSpan('full'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Publishing Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Feature this post')
                                            ->helperText('Featured posts will be highlighted on the website'),

                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'pending' => 'Pending Review',
                                                'published' => 'Published',
                                                'archived' => 'Archived',
                                            ])
                                            ->default('draft')
                                            ->required(),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date & Time')
                                            ->helperText('When this post should be published')
                                            ->nullable(),

                                        Forms\Components\DateTimePicker::make('scheduled_at')
                                            ->label('Schedule For')
                                            ->helperText('Schedule this post to be published later')
                                            ->nullable(),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO & Extra')
                            ->schema([
                                Forms\Components\Section::make('SEO Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('SEO Title')
                                            ->helperText('Leave empty to use post title. Max 60 characters.')
                                            ->maxLength(60),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('SEO Description')
                                            ->helperText('Brief description for search engines. Max 160 characters.')
                                            ->maxLength(160)
                                            ->rows(3),

                                        Forms\Components\Select::make('locale')
                                            ->options([
                                                'en' => 'English',
                                                'id' => 'Indonesian',
                                                // Add more locales as needed
                                            ])
                                            ->default('en')
                                            ->required(),

                                        Forms\Components\KeyValue::make('options')
                                            ->label('Additional Options')
                                            ->keyLabel('Option Name')
                                            ->valueLabel('Option Value')
                                            ->addable()
                                            ->reorderable()
                                            ->columnSpan('full'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Media Gallery')
                                    ->schema([
                                        MediaManagerInput::make('gallery')
                                            ->label('Gallery Images')
                                            // ->collection('gallery')
                                            // ->multiple()
                                            // ->maxFiles(10)
                                            ->hiddenLabel()
                                            ->schema([])
                                            ->columnSpan('full'),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Image')
                    ->collection('featured')
                    ->defaultImageUrl(fn(Post $record) => $record->getFeaturedImageUrl('thumbnail') ?? asset('images/post-placeholder.jpg')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'draft',
                        'warning' => 'pending',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),

                Tables\Columns\TextColumn::make('reading_time')
                    ->label('Reading')
                    ->suffix(' min')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Post Preview')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('featured_image')
                            ->label('Featured Image')
                            ->collection('featured')
                            ->conversion('large')
                            ->defaultImageUrl(asset('images/post-placeholder.jpg')),

                        TextEntry::make('title')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('content_overview')
                            ->label('Overview')
                            ->markdown(),
                    ])
                    ->collapsible(),

                Section::make('Details')
                    ->schema([
                        TextEntry::make('author.name')
                            ->label('Author'),

                        TextEntry::make('category.name')
                            ->label('Category'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'draft' => 'danger',
                                'pending' => 'warning',
                                'published' => 'success',
                                'archived' => 'gray',
                            }),

                        IconEntry::make('is_featured')
                            ->label('Featured')
                            ->boolean(),

                        TextEntry::make('reading_time')
                            ->label('Reading Time')
                            ->suffix(' minutes'),

                        TextEntry::make('view_count')
                            ->label('Views'),

                        TextEntry::make('comments_count')
                            ->label('Comments'),

                        TextEntry::make('published_at')
                            ->label('Published Date')
                            ->date(),

                        TextEntry::make('tags')
                            ->badge(),
                    ])
                    ->columns(3),

                Section::make('Content')
                    ->schema([
                        TextEntry::make('content_html')
                            ->label('Content')
                            ->html(),
                    ])
                    ->collapsible(),

                Section::make('SEO Information')
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('SEO Title')
                            ->default(fn($record) => $record->meta_title ?: $record->title),

                        TextEntry::make('meta_description')
                            ->label('SEO Description'),

                        TextEntry::make('slug')
                            ->label('URL Slug'),

                        TextEntry::make('locale')
                            ->label('Language'),

                        TextEntry::make('options')
                            ->listWithLineBreaks(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('System Information')
                    ->schema([
                        TextEntry::make('creator.name')
                            ->label('Created By'),

                        TextEntry::make('created_at')
                            ->dateTime(),

                        TextEntry::make('updater.name')
                            ->label('Updated By'),

                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->collapsible(),
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
            // 'view' => Pages\ViewPost::route('/{record}'),
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'content_overview', 'content_raw'];
    }
}
