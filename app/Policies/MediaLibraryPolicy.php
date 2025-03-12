<?php

namespace App\Policies;

use App\Models\MediaLibrary;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MediaLibraryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->tokenCan("read") 
                && (bool)$user->status === true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MediaLibrary $mediaLibrary): bool
    {
        return $user->tokenCan("read") 
                && (bool)$user->status === true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tokenCan("create") 
                && (bool)$user->status === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MediaLibrary $mediaLibrary): bool
    {
        return $user->tokenCan("update") 
                && (bool)$user->status === true
                && $user->ulid === $mediaLibrary->user_ulid;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MediaLibrary $mediaLibrary): bool
    {
        return $user->tokenCan("delete")
                && (bool)$user->status === true
                && $user->ulid === $mediaLibrary->user_ulid;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MediaLibrary $mediaLibrary): bool
    {
        return $user->tokenCan("delete")
                && (bool)$user->status === true
                && $user->ulid === $mediaLibrary->user_ulid;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MediaLibrary $mediaLibrary): bool
    {
        return $user->tokenCan("delete")
                && (bool)$user->status === true
                && $user->ulid === $mediaLibrary->user_ulid;
    }
}
