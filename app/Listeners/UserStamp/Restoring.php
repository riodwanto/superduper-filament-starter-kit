<?php

namespace App\Listeners\UserStamp;

use App\Support\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Event listener for model restoring events
 */
class Restoring
{
    /**
     * Handle the restoring event
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
            // Clear deleted_by field
            $model->{$model->getDeletedByColumn()} = null;

            // Clear deleted_by_team field if team stamping is enabled
            if ($model->isTeamStamping() && !is_null($model->getDeletedByTeamColumn())) {
                $model->{$model->getDeletedByTeamColumn()} = null;
            }

            // Set updated_by to current user if column exists
            if (!is_null($model->getUpdatedByColumn())) {
                $userId = UserStamp::getUserId();

                if (UserStamp::isValidUserId($userId)) {
                    $model->{$model->getUpdatedByColumn()} = $userId;

                    // Set updated_by_team if team stamping is enabled
                    if ($model->isTeamStamping() && !is_null($model->getUpdatedByTeamColumn())) {
                        $model->{$model->getUpdatedByTeamColumn()} = UserStamp::getTeamId();
                    }
                }
            }
        } catch (\Exception $e) {
            if (config('userstamp.log_stamping_errors', true)) {
                Log::error('Error during user stamping (restoring): ' . $e->getMessage());
            }
        }
    }
}
