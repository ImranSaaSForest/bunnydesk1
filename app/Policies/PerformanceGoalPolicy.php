<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Performance\PerformanceGoal;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformanceGoalPolicy
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
        return $user->hasPermissionTo('Employee List');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performance\PerformanceGoal $performanceGoal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PerformanceGoal $performanceGoal)
    {
        return $user->hasPermissionTo('Manage Performance Goals');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('Manage Performance Goals');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performance\PerformanceGoal $performanceGoal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PerformanceGoal $performanceGoal)
    {
        return $user->hasPermissionTo('Manage Performance Goals');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performance\PerformanceGoal $performanceGoal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PerformanceGoal $performanceGoal)
    {
        return $user->hasPermissionTo('Manage Performance Goals');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Performance\PerformanceGoal $performanceGoal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PerformanceGoal $performanceGoal)
    {
        return $user->hasPermissionTo('Manage Performance Goals');
    }
}
