<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isContributor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Anyone can view, but restricted to admin panel
        return $user->isAdmin() || $user->isContributor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isContributor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Admin can update any post
        if ($user->isAdmin()) {
            return true;
        }

        // Contributors can only update their own posts
        if ($user->isContributor()) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Only admin can delete posts
        if ($user->isAdmin()) {
            return true;
        }

        // Contributors cannot delete posts
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    /**
     * Custom policy: determine if user can publish/unpublish posts
     */
    public function publish(User $user, Post $post): bool
    {
        // Only admin can publish posts
        return $user->isAdmin();
    }

    /**
     * Custom policy: determine if user can view draft posts
     */
    public function viewDraft(User $user, Post $post): bool
    {
        // Admin can view any draft
        if ($user->isAdmin()) {
            return true;
        }

        // Contributors can only view their own drafts
        if ($user->isContributor()) {
            return $user->id === $post->user_id;
        }

        return false;
    }
}
