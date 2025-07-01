<?php

namespace App\Policies;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $user->hasRolesInProject($project, [
            ProjectRole::ADMIN,
            ProjectRole::VIEWER,
        ]);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasRolesInProject($project, [
            ProjectRole::ADMIN,
        ]);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isOwnerOfProject($project);
    }
}
