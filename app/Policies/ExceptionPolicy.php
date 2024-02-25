<?php

namespace App\Policies;

use App\Models\User;
use BezhanSalleh\FilamentExceptions\Models\Exception;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExceptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_exception');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function view(User $user, Exception $exception): bool
    {
        return $user->can('view_exception');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_exception');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function update(User $user, Exception $exception): bool
    {
        return $user->can('update_exception');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function delete(User $user, Exception $exception): bool
    {
        return $user->can('delete_exception');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_exception');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function forceDelete(User $user, Exception $exception): bool
    {
        return $user->can('force_delete_exception');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_exception');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function restore(User $user, Exception $exception): bool
    {
        return $user->can('restore_exception');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_exception');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \BezhanSalleh\FilamentExceptions\Models\Exception  $exception
     * @return bool
     */
    public function replicate(User $user, Exception $exception): bool
    {
        return $user->can('replicate_exception');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_exception');
    }

}
