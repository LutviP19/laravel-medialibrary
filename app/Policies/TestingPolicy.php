<?php

namespace App\Policies;

use App\Models\Testing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->tokenCan("read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Testing $testing): bool
    {
        return $user->tokenCan("read");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tokenCan("create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Testing $testing): bool
    {
        return $user->tokenCan("update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Testing $testing): bool
    {
        return $user->tokenCan("delete");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Testing $testing): bool
    {
        return $user->tokenCan("delete");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Testing $testing): bool
    {
        return $user->tokenCan("delete");
    }
}
