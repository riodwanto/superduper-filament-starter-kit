<?php

namespace App\Traits;

use App\Scopes\UserStampScope;
use App\Support\UserStamp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * HasUserStamp trait
 *
 * This trait provides automatic user tracking for Eloquent models.
 * It records which users create, update, and delete records.
 */
trait HasUserStamp
{
    /**
     * Whether user stamping is enabled for this model instance
     *
     * @var bool
     */
    protected bool $userstamping = true;

    /**
     * Whether team stamping is enabled for this model instance
     *
     * @var bool
     */
    protected bool $teamStamping = false;
    /**
     * Default value for team stamping
     *
     * @var bool
     */
    protected static $defaultTeamStamping = false;

    /**
     * Boot the trait
     *
     * @return void
     */
    public static function bootHasUserStamp(): void
    {
        static::addGlobalScope(new UserStampScope);
        static::registerListeners();

        // Initialize team stamping from config
        static::$defaultTeamStamping = Config::get('userstamp.enable_team_stamping', false);
    }

    /**
     * Initialize the trait
     *
     * @return void
     */
    public function initializeHasUserStamp()
    {
        $this->teamStamping = static::$defaultTeamStamping;
    }

    /**
     * Register model event listeners
     *
     * @return void
     */
    public static function registerListeners(): void
    {
        static::creating('App\Listeners\UserStamp\Creating@handle');
        static::updating('App\Listeners\UserStamp\Updating@handle');

        if (static::usingSoftDeletes()) {
            static::deleting('App\Listeners\UserStamp\Deleting@handle');
            static::restoring('App\Listeners\UserStamp\Restoring@handle');
        }
    }

    /**
     * Check if the model uses soft deletes
     *
     * @return bool
     */
    public static function usingSoftDeletes(): bool
    {
        static $usingSoftDeletes;

        if (is_null($usingSoftDeletes)) {
            return $usingSoftDeletes = in_array(
                'Illuminate\Database\Eloquent\SoftDeletes',
                class_uses_recursive(get_called_class())
            );
        }

        return $usingSoftDeletes;
    }

    /**
     * Get the model's creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo($this->getUserClass(), $this->getCreatedByColumn());
    }

    /**
     * Get the model's editor (last updater)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editor()
    {
        return $this->belongsTo($this->getUserClass(), $this->getUpdatedByColumn());
    }

    /**
     * Get the model's destroyer (who deleted it)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destroyer()
    {
        return $this->belongsTo($this->getUserClass(), $this->getDeletedByColumn());
    }

    /**
     * Get the model's creator team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function creatorTeam()
    {
        if (!$this->isTeamStamping() || is_null($this->getCreatedByTeamColumn())) {
            return null;
        }

        return $this->belongsTo($this->getTeamClass(), $this->getCreatedByTeamColumn());
    }

    /**
     * Get the model's editor team (last updater team)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function editorTeam()
    {
        if (!$this->isTeamStamping() || is_null($this->getUpdatedByTeamColumn())) {
            return null;
        }

        return $this->belongsTo($this->getTeamClass(), $this->getUpdatedByTeamColumn());
    }

    /**
     * Get the model's destroyer team (which team deleted it)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function destroyerTeam()
    {
        if (!$this->isTeamStamping() || is_null($this->getDeletedByTeamColumn())) {
            return null;
        }

        return $this->belongsTo($this->getTeamClass(), $this->getDeletedByTeamColumn());
    }

    /**
     * Get the created by column name
     *
     * @return string|null
     */
    public function getCreatedByColumn(): ?string
    {
        return defined('static::CREATED_BY')
            ? constant(static::class . '::CREATED_BY')
            : config('userstamp.columns.created_by');
    }

    /**
     * Get the updated by column name
     *
     * @return string|null
     */
    public function getUpdatedByColumn(): ?string
    {
        return defined('static::UPDATED_BY')
            ? constant(static::class . '::UPDATED_BY')
            : config('userstamp.columns.updated_by');
    }

    /**
     * Get the deleted by column name
     *
     * @return string|null
     */
    public function getDeletedByColumn(): ?string
    {
        return defined('static::DELETED_BY')
            ? constant(static::class . '::DELETED_BY')
            : config('userstamp.columns.deleted_by');
    }

    /**
     * Get the created by team column name
     *
     * @return string|null
     */
    public function getCreatedByTeamColumn(): ?string
    {
        return defined('static::CREATED_BY_TEAM')
            ? constant(static::class . '::CREATED_BY_TEAM')
            : config('userstamp.columns.created_by_team');
    }

    /**
     * Get the updated by team column name
     *
     * @return string|null
     */
    public function getUpdatedByTeamColumn(): ?string
    {
        return defined('static::UPDATED_BY_TEAM')
            ? constant(static::class . '::UPDATED_BY_TEAM')
            : config('userstamp.columns.updated_by_team');
    }

    /**
     * Get the deleted by team column name
     *
     * @return string|null
     */
    public function getDeletedByTeamColumn(): ?string
    {
        return defined('static::DELETED_BY_TEAM')
            ? constant(static::class . '::DELETED_BY_TEAM')
            : config('userstamp.columns.deleted_by_team');
    }

    /**
     * Check if user stamping is enabled
     *
     * @return bool
     */
    public function isUserstamping(): bool
    {
        return $this->userstamping;
    }

    /**
     * Check if team stamping is enabled
     *
     * @return bool
     */
    public function isTeamStamping(): bool
    {
        return $this->teamStamping;
    }

    /**
     * Disable user stamping for this model instance
     *
     * @return void
     */
    public function stopUserstamping(): void
    {
        $this->userstamping = false;
    }

    /**
     * Enable user stamping for this model instance
     *
     * @return void
     */
    public function startUserstamping(): void
    {
        $this->userstamping = true;
    }

    /**
     * Enable team stamping for this model instance
     *
     * @return void
     */
    public function enableTeamStamping(): void
    {
        $this->teamStamping = true;
    }

    /**
     * Disable team stamping for this model instance
     *
     * @return void
     */
    public function disableTeamStamping(): void
    {
        $this->teamStamping = false;
    }

    /**
     * Get the user class
     *
     * @return string
     */
    protected function getUserClass(): string
    {
        return config('userstamp.user_model', config('auth.providers.users.model'));
    }

    /**
     * Get the team class
     *
     * @return string
     */
    protected function getTeamClass(): string
    {
        return config('userstamp.team_model', 'App\\Models\\Team');
    }

    /**
     * Get accessor for creator
     *
     * @return mixed
     */
    public function getCreatorAttribute()
    {
        return $this->creator()->first();
    }

    /**
     * Get accessor for editor
     *
     * @return mixed
     */
    public function getEditorAttribute()
    {
        return $this->editor()->first();
    }

    /**
     * Get accessor for destroyer
     *
     * @return mixed
     */
    public function getDestroyerAttribute()
    {
        return $this->destroyer()->first();
    }

    /**
     * Check if the model should filter by current user
     *
     * @return bool
     */
    public function shouldFilterByCurrentUser(): bool
    {
        return false; // Override in model if needed
    }
}
