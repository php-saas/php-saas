<?php

namespace App\Actions\Project;

use App\Events\ProjectDeleted;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteProject
{
    /**
     * @param  array<mixed, mixed>  $input
     */
    public function delete(User $user, Project $project, array $input): void
    {
        Validator::make($input, [
            'name' => 'required',
        ])->validate();

        if ($user->ownedProjects()->count() === 1) {
            throw ValidationException::withMessages([
                'name' => __('Cannot delete the last project.'),
            ]);
        }

        if ($user->current_project_id == $project->id) {
            throw ValidationException::withMessages([
                'name' => __('Cannot delete your current project.'),
            ]);
        }

        $user->currentProject();

        $project->delete();

        event(new ProjectDeleted($project));
    }
}
