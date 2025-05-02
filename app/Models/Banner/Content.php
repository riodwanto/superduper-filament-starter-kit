<?php

namespace App\Models\Banner;

use App\Models\User;
use App\Traits\HasUserStamp;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Content extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory, HasUlids, SoftDeletes;
    use HasUserStamp;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banner_contents';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'banner_category_id',
        'sort',
        'title',
        'description',
        'click_url',
        'click_url_target',
        'is_active',
        'start_date',
        'end_date',
        'published_at',
        'locale',
        'options',
        'created_by',
        'updated_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'published_at' => 'datetime',
        'options' => 'json',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    /**
     * Get the category that owns the banner.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'banner_category_id');
    }

    /**
     * Get the user who created this banner.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this banner.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('preview')
            ->format('webp')
            ->quality(90)
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        // Add responsive image sizes - always convert to WebP
        $this->addMediaConversion('thumbnail')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 1200, 800)
            ->nonQueued();
    }

    /**
     * Get the banner image URL
     * @param string $conversion
     * @return string|null
     */
    public function getImageUrl(string $conversion = ''): ?string
    {
        $media = $this->getFirstMedia('banners');

        if (!$media) {
            return null;
        }

        return $conversion ? $media->getUrl($conversion) : $media->getUrl();
    }

    /**
     * Check if the banner has an image
     * @return bool
     */
    public function hasImage(): bool
    {
        return $this->hasMedia('banners');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banners')
            ->singleFile();
    }

    /**
     * Get active banners only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Increment impression count
     */
    public function trackImpression()
    {
        $this->increment('impression_count');
    }

    /**
     * Increment click count
     */
    public function trackClick()
    {
        $this->increment('click_count');
    }
}
