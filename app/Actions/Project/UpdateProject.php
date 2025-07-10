<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateProject
{
    public function update(Project $project, array $input): Project
    {
        $this->validate($project, $input);

        $project->fill([
            'name' => $input['name'],
        ]);
        $project->save();

        return $project;
    }

    private function validate(Project $project, array $input): void
    {
        Validator::make($input, [
            'name' => [
                'required',
                Rule::unique('projects')->where('owner_id', $project->owner_id)->ignore($project->id),
            ],
        ])->validate();
    }
}
