<?php

namespace App\Models;

use App\Events\ContactUsCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class ContactUs extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'company',
        'employees',
        'title',
        'subject',
        'message',
        'status',
        'reply_subject',
        'reply_message',
        'replied_at',
        'replied_by_user_id',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'replied_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'ip_address',
        'user_agent',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => ContactUsCreated::class,
    ];

    /**
     * Get the user who replied to this contact request.
     */
    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by_user_id');
    }

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Scope a query to search for text in multiple columns.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $searchTerm)
    {
        if (empty($searchTerm)) {
            return $query;
        }

        // Always use the LIKE search for testing to ensure consistent results
        if (app()->environment('testing')) {
            return $this->searchWithLike($query, $searchTerm);
        }

        // For production, try fulltext search first if available
        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                // Check if the fulltext index exists
                $hasFulltextIndex = false;
                $indexes = DB::select("SHOW INDEX FROM {$this->table} WHERE Index_type = 'FULLTEXT'");

                if (!empty($indexes)) {
                    $hasFulltextIndex = true;
                }

                if ($hasFulltextIndex) {
                    return $query->whereRaw('MATCH(firstname, lastname, email, subject, message) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
                }
            }
        } catch (Exception $e) {
            // Silently fail and use the fallback
        }

        // Fallback to LIKE search if fulltext is not available or fails
        return $this->searchWithLike($query, $searchTerm);
    }

    public static function seedCreate(array $attributes = [])
    {
        // Temporarily disable event dispatcher
        $dispatcher = self::getEventDispatcher();
        self::unsetEventDispatcher();

        // Create the model
        $model = self::create($attributes);

        // Restore the event dispatcher
        self::setEventDispatcher($dispatcher);

        return $model;
    }

    /**
     * Fallback search using LIKE for when fulltext isn't available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchWithLike($query, string $searchTerm)
    {
        $searchTerm = '%' . $searchTerm . '%';
        return $query->where(function($q) use ($searchTerm) {
            $q->where('firstname', 'LIKE', $searchTerm)
              ->orWhere('lastname', 'LIKE', $searchTerm)
              ->orWhere('email', 'LIKE', $searchTerm)
              ->orWhere('subject', 'LIKE', $searchTerm)
              ->orWhere('message', 'LIKE', $searchTerm);
        });
    }

    /**
     * Mark the contact request as read.
     *
     * @return $this
     */
    public function markAsRead()
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }

        return $this;
    }

    /**
     * Add a reply to the contact request.
     *
     * @param string $subject
     * @param string $message
     * @param User|null $user
     * @return $this
     */
    public function addReply(string $subject, string $message, ?User $user = null)
    {
        $this->update([
            'reply_subject' => $subject,
            'reply_message' => $message,
            'replied_at' => now(),
            'replied_by_user_id' => $user?->id,
            'status' => 'responded'
        ]);

        return $this;
    }
}
