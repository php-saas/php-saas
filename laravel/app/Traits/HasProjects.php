<?php

namespace App\Traits;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection<int, Project> $ownedProjects
 * @property Collection<int, Project> $invitedProjects
 */
trait HasProjects
{
    public function currentProject(): Project
    {
        /** @var ?Project $project */
        $project = Project::query()->find($this->current_project_id);

        if (! $project || (! $this->isOwnerOfProject($project) && ! $this->isUserOfProject($project))) {
            $project = $this->ownedProjects()->first();
        }

        if (! $project) {
            $project = new Project([
                'name' => 'Default Project',
                'owner_id' => $this->id,
            ]);
            $project->save();
        }

        /** @var Project $project */
        if ($this->current_project_id !== $project->id) {
            $this->current_project_id = $project->id;
            $this->save();
        }

        return $project;
    }

    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function allProjects(): Builder
    {
        return Project::query()
            ->where('owner_id', $this->id)
            ->orWhereHas('users', fn (Builder $q) => $q->where('user_id', $this->id));
    }

    public function isUserOfProject(?Project $project): bool
    {
        return $project && $project->users()->where('user_id', $this->id)->exists();
    }

    public function isOwnerOfProject(?Project $project): bool
    {
        return $project?->owner_id === $this->id;
    }

    public function hasRolesInProject(Project $project, array $roles): bool
    {
        if ($this->isOwnerOfProject($project)) {
            return true;
        }

        return $project->users()
            ->where('user_id', $this->id)
            ->whereIn('role', $roles)
            ->exists();
    }
}
