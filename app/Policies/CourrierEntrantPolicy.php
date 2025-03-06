<?php

namespace App\Policies;

use App\Models\CourriersEntrants;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourrierEntrantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->canViewCourriers();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourriersEntrants  $courriersEntrants
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CourriersEntrants $courriersEntrants)
    {
        return $user->canViewCourriers();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canManageCourriers();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourriersEntrants  $courriersEntrants
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CourriersEntrants $courriersEntrants)
    {
        return $user->canManageCourriers();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourriersEntrants  $courriersEntrants
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CourriersEntrants $courriersEntrants)
    {
        return $user->hasRole(['admin', 'gestionnaire']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourriersEntrants  $courriersEntrants
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CourriersEntrants $courriersEntrants)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourriersEntrants  $courriersEntrants
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CourriersEntrants $courriersEntrants)
    {
        return $user->isAdmin();
    }
}