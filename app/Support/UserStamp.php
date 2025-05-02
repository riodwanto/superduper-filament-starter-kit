<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class for handling user stamping functionality
 *
 * This class provides methods to resolve and retrieve the current user ID
 * for stamping database records with user information.
 */
class UserStamp
{
    /**
     * Callback for resolving the user ID
     *
     * @var callable|null
     */
    protected static $resolveUsingCallback = null;

    /**
     * Cached user ID to minimize repeated lookups
     *
     * @var mixed|null
     */
    protected static $cachedUserId = null;

    /**
     * Set a custom callback for resolving the user ID
     *
     * @param callable $callback Function that returns a user ID
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function resolveUsing(callable $callback): void
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('User resolver must be callable');
        }

        static::$resolveUsingCallback = $callback;
        static::clearUserIdCache(); // Clear cache when resolver changes
    }

    /**
     * Get the current user ID for stamping
     *
     * @return mixed User ID or null if not available
     */
    public static function getUserId(): mixed
    {
        try {
            if (is_null(static::$cachedUserId)) {
                static::$cachedUserId = is_null(static::$resolveUsingCallback)
                    ? Auth::id()
                    : call_user_func(static::$resolveUsingCallback);
            }

            return static::$cachedUserId;
        } catch (\Exception $e) {
            Log::error('Failed to resolve user ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear the cached user ID
     *
     * @return void
     */
    public static function clearUserIdCache(): void
    {
        static::$cachedUserId = null;
    }

    /**
     * Check if the provided user ID is valid
     *
     * @param mixed $userId User ID to validate
     * @return bool
     */
    public static function isValidUserId($userId): bool
    {
        // Basic validation - customize based on your requirements
        return !is_null($userId) && ($userId !== false);
    }

    /**
     * Get team ID if applicable (for team-based stamping)
     *
     * @return mixed Team ID or null if not available
     */
    public static function getTeamId(): mixed
    {
        // Implement team resolution logic here
        // Example: return Auth::user()?->current_team_id;
        return config('userstamp.default_team_id', null);
    }
}
