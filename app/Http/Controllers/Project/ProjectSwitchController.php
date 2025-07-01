<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

class ProjectSwitchController extends Controller
{
    public function __invoke(Project $project): RedirectResponse
    {
        $this->authorize('view', $project);

        user()->update([
            'current_project_id' => $project->id,
        ]);

        return redirect()->route('dashboard');
    }
}
