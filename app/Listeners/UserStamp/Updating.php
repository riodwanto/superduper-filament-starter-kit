<?php

namespace App\Listeners\UserStamp;

use App\Support\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Event listener for model updating events
 */
class Updating
{
    /**
     * Handle the updating event
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function handle(Model $model): void
    {
        // Skip if stamping is disabled or column doesn't exist
        if (!$model->isUserstamping() || is_null($model->getUpdatedByColumn())) {
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

            // Set updated_by if user ID is valid
            if (UserStamp::isValidUserId($userId)) {
                $model->{$model->getUpdatedByColumn()} = $userId;

                // Handle team stamping if enabled
                if ($model->isTeamStamping() && !is_null($model->getUpdatedByTeamColumn())) {
                    $model->{$model->getUpdatedByTeamColumn()} = UserStamp::getTeamId();
                }
            }
        } catch (\Exception $e) {
            if (config('userstamp.log_stamping_errors', true)) {
                Log::error('Error during user stamping (updating): ' . $e->getMessage());
            }
        }
    }
}
