<?php

namespace App\Scopes;

use App\Support\UserStamp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Log;

/**
 * Global scope for user stamp functionality
 */
class UserStampScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Apply any global filtering if needed
        if ($model->shouldFilterByCurrentUser()) {
            $builder->where($model->getCreatedByColumn(), UserStamp::getUserId());
        }
    }

    /**
     * Extend the query builder with custom macros
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function extend(Builder $builder): void
    {
        // Add updateWithUserstamps macro
        $builder->macro('updateWithUserstamps', function (Builder $builder, $values) {
            try {
                if (!$builder->getModel()->isUserstamping()) {
                    return $builder->update($values);
                }

                $userId = UserStamp::getUserId();

                // Skip setting user ID if not available and config says to skip
                if (is_null($userId) && config('userstamp.skip_when_no_user', true)) {
                    return $builder->update($values);
                }

                // Use default user ID if configured
                if (is_null($userId)) {
                    $userId = config('userstamp.default_user_id');
                }

                // Only set user ID if it's valid
                if (UserStamp::isValidUserId($userId) && !is_null($builder->getModel()->getUpdatedByColumn())) {
                    $values[$builder->getModel()->getUpdatedByColumn()] = $userId;

                    // Set team ID if team stamping is enabled
                    if ($builder->getModel()->isTeamStamping() && !is_null($builder->getModel()->getUpdatedByTeamColumn())) {
                        $values[$builder->getModel()->getUpdatedByTeamColumn()] = UserStamp::getTeamId();
                    }
                }

                return $builder->update($values);
            } catch (\Exception $e) {
                if (config('userstamp.log_stamping_errors', true)) {
                    Log::error('Error during updateWithUserstamps: ' . $e->getMessage());
                }

                // Fallback to standard update
                return $builder->update($values);
            }
        });

        // Add deleteWithUserstamps macro
        $builder->macro('deleteWithUserstamps', function (Builder $builder) {
            try {
                if (!$builder->getModel()->isUserstamping()) {
                    return $builder->delete();
                }

                $userId = UserStamp::getUserId();

                // Skip setting user ID if not available and config says to skip
                if (is_null($userId) && config('userstamp.skip_when_no_user', true)) {
                    return $builder->delete();
                }

                // Use default user ID if configured
                if (is_null($userId)) {
                    $userId = config('userstamp.default_user_id');
                }

                // Only update deleted_by if user ID is valid and column exists
                if (UserStamp::isValidUserId($userId) && !is_null($builder->getModel()->getDeletedByColumn())) {
                    $values = [
                        $builder->getModel()->getDeletedByColumn() => $userId,
                    ];

                    // Set team ID if team stamping is enabled
                    if ($builder->getModel()->isTeamStamping() && !is_null($builder->getModel()->getDeletedByTeamColumn())) {
                        $values[$builder->getModel()->getDeletedByTeamColumn()] = UserStamp::getTeamId();
                    }

                    $builder->update($values);
                }

                return $builder->delete();
            } catch (\Exception $e) {
                if (config('userstamp.log_stamping_errors', true)) {
                    Log::error('Error during deleteWithUserstamps: ' . $e->getMessage());
                }

                // Fallback to standard delete
                return $builder->delete();
            }
        });

        // Add filterByUser macro
        $builder->macro('filterByUser', function (Builder $builder, $userId = null) {
            if (is_null($userId)) {
                $userId = UserStamp::getUserId();
            }

            return $builder->where($builder->getModel()->getCreatedByColumn(), $userId);
        });

        // Add withoutUserStamping macro
        $builder->macro('withoutUserStamping', function (Builder $builder, callable $callback) {
            $model = $builder->getModel();
            $originalStampingValue = $model->isUserstamping();

            $model->stopUserstamping();

            try {
                return $callback($builder);
            } finally {
                if ($originalStampingValue) {
                    $model->startUserstamping();
                }
            }
        });
    }
}
