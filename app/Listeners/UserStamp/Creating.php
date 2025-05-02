<?php

namespace App\Listeners\UserStamp;

use App\Support\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Event listener for model creating events
 */
class Creating
{
    /**
     * Handle the creating event
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function handle(Model $model): void
    {
        // Skip if stamping is disabled or column doesn't exist
        if (!$model->isUserstamping() || is_null($model->getCreatedByColumn())) {
            return;
        }

        try {
            // Get user ID
            $userId = UserStamp::getUserId();

            // Skip if no user ID and config says to skip
            if (is_null($userId) && config('userstamp.skip_when_no_user', true)) {
                return;
            }

            // Use default user ID if configured
            if (is_null($userId)) {
                $userId = config('userstamp.default_user_id');
            }

            // Set created_by if not already set and user ID is valid
            if (is_null($model->{$model->getCreatedByColumn()}) && UserStamp::isValidUserId($userId)) {
                $model->{$model->getCreatedByColumn()} = $userId;

                // Handle team stamping if enabled
                if ($model->isTeamStamping() && !is_null($model->getCreatedByTeamColumn())) {
                    $model->{$model->getCreatedByTeamColumn()} = UserStamp::getTeamId();
                }
            }

            // Set updated_by if not already set and column exists
            if (
                !is_null($model->getUpdatedByColumn()) &&
                is_null($model->{$model->getUpdatedByColumn()}) &&
                UserStamp::isValidUserId($userId)
            ) {
                $model->{$model->getUpdatedByColumn()} = $userId;

                // Handle team stamping if enabled
                if ($model->isTeamStamping() && !is_null($model->getUpdatedByTeamColumn())) {
                    $model->{$model->getUpdatedByTeamColumn()} = UserStamp::getTeamId();
                }
            }
        } catch (\Exception $e) {
            if (config('userstamp.log_stamping_errors', true)) {
                Log::error('Error during user stamping (creating): ' . $e->getMessage());
            }
        }
    }
}
