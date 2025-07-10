<?php

namespace App\Actions\Project;

use App\Enums\ProjectRole;
use App\Mail\ProjectInvitation;
use App\Models\Project;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InviteToProject
{
    public function invite(Project $project, array $input): void
    {
        $this->validate($project, $input);

        $project->users()->create([
            'email' => $input['email'],
            'role' => $input['role'],
        ]);

        Mail::to($input['email'])->send(new ProjectInvitation($project));
    }

    protected function validate(Project $project, array $input): void
    {
        Validator::make($input, $this->rules($project), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->validate();
    }

    protected function rules(Project $project): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('project_user')->where(function (Builder $query) use ($project) {
                    $query->where('project_id', $project->id);
                }),
                Rule::notIn([
                    ...$project->registeredUsers()->pluck('users.email'),
                    $project->owner->email,
                ]),
            ],
            'role' => [
                'required',
                Rule::in([
                    ProjectRole::ADMIN,
                    ProjectRole::VIEWER,
                ]),
            ],
        ];
    }
}
