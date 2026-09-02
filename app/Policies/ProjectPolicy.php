<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admins can view any project. Users can only view their own.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->user_id === $user->id;
    }

    /**
     * Any authenticated user can create a project.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Users can only update their own draft projects.
     * Admins can update any project.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->user_id === $user->id && $project->isDraft();
    }

    /**
     * Only admins can delete projects.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can view internal cost data.
     */
    public function viewCostData(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can approve projects.
     */
    public function approve(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }
}
