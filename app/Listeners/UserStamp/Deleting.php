<?php

namespace App\Listeners\UserStamp;

use App\Support\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Event listener for model deleting events
 */
class Deleting
{
    /**
     * Handle the deleting event
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function handle(Model $model): void
    {
        // Skip if stamping is disabled or column doesn't exist
        if (!$model->isUserstamping() || is_null($model->getDeletedByColumn())) {
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

            // Set deleted_by if not already set and user ID is valid
            if (is_null($model->{$model->getDeletedByColumn()}) && UserStamp::isValidUserId($userId)) {
                $model->{$model->getDeletedByColumn()} = $userId;

                // Handle team stamping if enabled
                if ($model->isTeamStamping() && !is_null($model->getDeletedByTeamColumn())) {
                    $model->{$model->getDeletedByTeamColumn()} = UserStamp::getTeamId();
                }

                // Save the model without triggering events to avoid infinite loop
                $model->withoutEvents(function () use ($model) {
                    $model->save();
                });
            }
        } catch (\Exception $e) {
            if (config('userstamp.log_stamping_errors', true)) {
                Log::error('Error during user stamping (deleting): ' . $e->getMessage());
            }
        }
    }
}
